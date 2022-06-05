<?php

declare(strict_types=1);

/**
 * This file is part of the Max package.
 *
 * (c) Cheng Yao <987861463@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exceptions;

use Max\HttpServer\ExceptionHandler as HttpExceptionHandler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandler extends HttpExceptionHandler
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    protected function reportException(Throwable $throwable, ServerRequestInterface $request): void
    {
        $this->logger->error($throwable->getMessage(), [
            'file'    => $throwable->getFile(),
            'line'    => $throwable->getLine(),
            'headers' => $request->getHeaders(),
            'request' => $request->getQueryParams() + $request->getParsedBody()
        ]);
    }
}
