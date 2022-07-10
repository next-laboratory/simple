<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middlewares;

use Max\Http\Server\Middlewares\AllowCrossDomain as BaseAllowCrossDomain;

class AllowCrossDomain extends BaseAllowCrossDomain
{
    /** {@inheritdoc} */
    protected array $allowOrigin = [];
}
