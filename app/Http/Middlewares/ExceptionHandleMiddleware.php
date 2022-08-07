<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middlewares;

use App\Http\Response;
use Max\Exceptions\Handlers\VarDumperAbortHandler;
use Max\Exceptions\Handlers\WhoopsExceptionHandler;
use Max\Exceptions\VarDumperAbort;
use Max\Http\Message\Exceptions\HttpException;
use Max\Http\Server\Middlewares\ExceptionHandleMiddleware as HttpExceptionHandleMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandleMiddleware extends HttpExceptionHandleMiddleware
{
    public function __construct(
        protected LoggerInterface $logger
    ) {
    }

    protected function renderException(Throwable $throwable, ServerRequestInterface $request): ResponseInterface
    {
        $message = $throwable->getMessage();
        $code    = $this->getStatusCode($throwable);
        if ($throwable instanceof VarDumperAbort) {
            return (new VarDumperAbortHandler())->handle($throwable, $request);
        }
        if (env('APP_DEBUG')) {
            $response = (new WhoopsExceptionHandler())->handle($throwable, $request);
            if ($throwable instanceof HttpException) {
                $response = $response->withStatus($throwable->getCode());
            }
            return $response;
        }
        return Response::HTML(<<<ETO
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error $code</title>
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
            margin: 30px;
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
            /* border-bottom: 2px solid #08e; */
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
            <h1>Error $code</h1>
        </header>
        <main>
            <p>$message</p>
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
        );
    }

    protected function reportException(Throwable $throwable, ServerRequestInterface $request): void
    {
        $this->logger->error($throwable->getMessage(), [
            'file'    => $throwable->getFile(),
            'line'    => $throwable->getLine(),
            'request' => $request,
            'trace'   => $throwable->getTrace(),
        ]);
    }
}
