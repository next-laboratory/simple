<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use App\Exception\WhoopsExceptionHandler;
use App\Http\Response;
use Max\Http\Message\Exception\HttpException;
use Max\Http\Server\Middleware\ExceptionHandleMiddleware as Middleware;
use Max\VarDumper\Abort;
use Max\VarDumper\AbortHandler;
use NunoMaduro\Collision\Adapters\Laravel\Inspector;
use NunoMaduro\Collision\Provider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class ExceptionHandleMiddleware extends Middleware
{
    use AbortHandler;

    public function __construct(
        protected LoggerInterface $logger
    ) {
    }

    protected function render(Throwable $throwable, ServerRequestInterface $request): ResponseInterface
    {
        if ($throwable instanceof Abort) {
            return Response::HTML($this->convertToHtml($throwable));
        }
        $code = $this->getStatusCode($throwable);
        if (env('APP_DEBUG')) {
            $response = (new WhoopsExceptionHandler())->handle($throwable, $request);
            if ($throwable instanceof HttpException) {
                $response = $response->withStatus($code);
            }
            return $response;
        }
        $message = $throwable->getMessage();
        return Response::HTML(
            <<<ETO
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error {$code}</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            background: #eee;
            font-family: monospace;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .box {
            background: #fff;
            border-radius: 3px;
            padding: 20px 30px;
            max-width: 600px;
            flex-grow: 1;
            font-size: 14px;
            color: #333;
            margin: 0 auto; /* if there's no flex */
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
        }

        h1 {
            color: #111;
            font-size: 20px;
            padding: 0 0 0 0;
            margin: 10px 0 8px 0;
        }

        ul {
            padding: 0 0 0 10px;
        }

        li {
            list-style-type: none;
        }

        li::before {
            content: '- ';
        }

        button {
            box-sizing: content-box;
            width: 100%;
            display: block;
            background: #48e;
            color: #fff;
            border-radius: 0 0 2px 2px;
            border: 0;
            padding: 15px 30px;
            margin: 20px -30px -20px -30px;
            font-weight: bold;
            font-family: monospace;
            box-shadow: 0 2px 4px rgba(0,0,0,.2) inset;
        }

        button:hover {
            background: #37d;
        }

        button:focus {
            outline: 0;
        }

        button:focus:not(:hover) {
            outline: 0;
            border: 2px solid #25d;
            padding: 13px 28px;
        }

        button:active {
            outline: 0;
            box-shadow: 0 0 6px rgba(0,0,0,.3) inset;
            border: 0;
            padding: 15px 30px;
        }
    </style>
</head>
<body>
    <div class="box">
        <header>
            <h1>Error {$code}</h1>
        </header>
        <main>
            <p>{$message}</p>
            <ul>
                <li>Try again later.</li>
                <li>Check if you visited the correct URL.</li>
                <li>Report this issue if you think this is a mistake.</li>
            </ul>
            <button type="button" onclick="window.location.reload()">Retry</button>
        </main>
    </div>
</body>
</html>
ETO
            ,
            $code
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    protected function report(Throwable $throwable, ServerRequestInterface $request): void
    {
        $this->logger->error($throwable->getMessage(), [
            'file'    => $throwable->getFile(),
            'line'    => $throwable->getLine(),
            'request' => $request,
            'trace'   => $throwable->getTrace(),
        ]);
        if (class_exists('NunoMaduro\Collision\Provider') && PHP_SAPI === 'cli') {
            $this->dump($throwable);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    protected function dump(Throwable $throwable)
    {
        $provider = make(Provider::class);
        $handler  = $provider->register()
                             ->getHandler()
                             ->setOutput(new ConsoleOutput());
        $handler->setInspector((new Inspector($throwable)));
        $handler->handle();
    }
}
