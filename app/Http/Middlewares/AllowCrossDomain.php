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

use Max\HttpServer\Middlewares\AllowCrossDomain as BaseAllowCrossDomain;

class AllowCrossDomain extends BaseAllowCrossDomain
{
    /** @var array $allowOrigin 允许域，全部可以使用`*` */
    protected array $allowOrigin = [];
}
