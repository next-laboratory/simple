<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use Max\Http\Server\Middleware\CORSMiddleware as Middleware;

class CORSMiddleware extends Middleware
{
    /** {@inheritdoc} */
    protected array $allowOrigin = ['*'];
}
