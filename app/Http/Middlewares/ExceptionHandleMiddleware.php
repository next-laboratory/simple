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

namespace App\Http\Middlewares;

use App\Exceptions\Handlers\AppExceptionHandler;
use App\Exceptions\Handlers\HttpExceptionHandler;
use Max\Framework\Exceptions\VarDumperAbortHandler;
use Max\Http\Server\Middlewares\ExceptionHandleMiddleware as HttpExceptionHandleMiddleware;

class ExceptionHandleMiddleware extends HttpExceptionHandleMiddleware
{
    /**
     * 异常处理类
     */
    protected array $exceptionHandlers = [
        VarDumperAbortHandler::class,
        HttpExceptionHandler::class,
        AppExceptionHandler::class,
    ];
}
