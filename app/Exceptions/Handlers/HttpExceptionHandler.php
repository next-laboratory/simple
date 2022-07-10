<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

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
        return Response::JSON([
            'status'  => false,
            'code'    => $statusCode = $throwable->getCode(),
            'data'    => [],
            'message' => $throwable->getMessage(),
        ], $statusCode);
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof HttpException;
    }
}
