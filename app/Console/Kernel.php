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

namespace App\Console;

use Max\Foundation\Console\Application;

class Kernel extends Application
{
    /**
     * 命令扫描路径
     *
     * @var array|string[]
     */
    protected array $scanDir = [
        __DIR__ . '/Commands',
    ];
}
