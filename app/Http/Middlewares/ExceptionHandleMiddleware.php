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

use Max\Http\Server\Middlewares\ExceptionHandleMiddleware as HttpExceptionHandleMiddleware;

class ExceptionHandleMiddleware extends HttpExceptionHandleMiddleware
{
    /**
     * 异常处理类
     */
    protected array $exceptionHandlers = [
        'Max\Framework\Exceptions\Handlers\VarDumperAbortHandler',
//        'App\Exceptions\Handlers\HttpExceptionHandler',
        'Max\Framework\Exceptions\Handlers\WhoopsExceptionHandler',
        'App\Exceptions\Handlers\AppExceptionHandler',
    ];
}
