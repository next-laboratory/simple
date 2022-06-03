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
use Throwable;

class ExceptionHandler extends HttpExceptionHandler
{
    /**
     * @param Throwable              $throwable
     * @param ServerRequestInterface $request
     *
     * @return void
     */
    protected function reportException(Throwable $throwable, ServerRequestInterface $request): void
    {
        if (PHP_SAPI === 'cli') {
            $this->dumpException($throwable);
        } else {
            echo $throwable->getMessage();
        }
    }

    /**
     * @param Throwable $throwable
     *
     * @return void
     */
    protected function dumpException(Throwable $throwable): void
    {
        echo sprintf("[%s] %s in %s +%d\n%s\n",
            $throwable::class,
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            $throwable->getTraceAsString()
        );
    }
}
