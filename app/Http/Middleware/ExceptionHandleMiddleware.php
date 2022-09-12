<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use App\Http\Response;
use Max\Http\Server\Contract\Renderable;
use Max\Http\Server\Middleware\ExceptionHandleMiddleware as Middleware;
use Max\VarDumper\Abort;
use Max\VarDumper\AbortHandler;
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
        protected LoggerInterface $logger,
    ) {
    }

    protected function render(Throwable $throwable, ServerRequestInterface $request): ResponseInterface
    {
        if ($throwable instanceof Abort) {
            return Response::HTML($this->convertToHtml($throwable));
        }
        if ($throwable instanceof Renderable) {
            return $throwable->render($request);
        }
        $statusCode = $this->getStatusCode($throwable);
        if (\App\env('APP_DEBUG')) {
            if (class_exists('Spatie\Ignition\Ignition')) {
                $ignition = new \Spatie\Ignition\Ignition();
                ob_start();
                $ignition->handleException($throwable);
                return Response::HTML(ob_get_clean(), $statusCode);
            }
            return parent::render($throwable, $request);
        }
        return Response::text($throwable->getMessage(), $statusCode);
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
        if (PHP_SAPI === 'cli' && class_exists('NunoMaduro\Collision\Provider')) {
            $this->dump($throwable);
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    protected function dump(Throwable $throwable)
    {
        $provider = make('NunoMaduro\Collision\Provider');
        $handler  = $provider->register()
                             ->getHandler()
                             ->setOutput(new ConsoleOutput());
        $handler->setInspector((new \NunoMaduro\Collision\Adapters\Laravel\Inspector($throwable)));
        $handler->handle();
    }
}
