<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middlewares;

use Max\Http\Server\Middlewares\ExceptionHandleMiddleware as HttpExceptionHandleMiddleware;

class ExceptionHandleMiddleware extends HttpExceptionHandleMiddleware
{
    /**
     * 异常处理类.
     */
    protected array $exceptionHandlers = [
        'Max\Framework\Exceptions\Handlers\VarDumperAbortHandler',
        //        'App\Exceptions\Handlers\HttpExceptionHandler',
        'Max\Framework\Exceptions\Handlers\WhoopsExceptionHandler',
        'App\Exceptions\Handlers\AppExceptionHandler',
    ];
}
