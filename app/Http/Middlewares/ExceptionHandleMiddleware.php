<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middlewares;

use Max\Framework\Exceptions\Handlers\VarDumperAbortHandler;
use Max\Framework\Exceptions\Handlers\WhoopsExceptionHandler;
use Max\Framework\Exceptions\VarDumperAbort;
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
        if ($throwable instanceof VarDumperAbort) {
            return (new VarDumperAbortHandler())->handle($throwable, $request);
        }
        return (new WhoopsExceptionHandler())->handle($throwable, $request);
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
