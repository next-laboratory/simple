<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use Next\Http\Server\Middleware\CORSMiddleware as Middleware;

class CORSMiddleware extends Middleware
{
    protected array $allowOrigin = ['*'];
}
