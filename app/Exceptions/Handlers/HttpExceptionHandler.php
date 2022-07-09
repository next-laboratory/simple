<?php

namespace App\Exceptions\Handlers;

use App\Http\Response;
use Max\Http\Message\Exceptions\HttpException;
use Max\Http\Server\Contracts\ExceptionHandlerInterface;
use Max\Http\Server\Contracts\StoppableExceptionHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class HttpExceptionHandler implements ExceptionHandlerInterface, StoppableExceptionHandlerInterface
{
    public function handle(Throwable $throwable, ServerRequestInterface $request): ?ResponseInterface
    {
        $statusCode = $throwable->getCode();
        return Response::JSON([
            'status'  => false,
            'code'    => $statusCode,
            'data'    => [],
            'message' => $throwable->getMessage()
        ], $statusCode);
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof HttpException;
    }
}
