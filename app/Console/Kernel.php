<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Console;

use Max\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * 注册命令.
     *
     * @var array<int, string> $commands
     */
    protected array $commands = [];
}
