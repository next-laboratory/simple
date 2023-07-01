<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use Max\Http\Server\Middleware\VerifyCSRFToken as Middleware;

class VerifyCSRFToken extends Middleware
{
    /**
     * 排除，不校验CSRF Token.
     */
    protected array $except = ['/'];
}
