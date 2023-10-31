<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Console;

use Next\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function commands(): array
    {
        return [
            'App\Console\Command\Server\SwooleServerCommand',
            'App\Console\Command\Server\SwooleCoServerCommand',
            'App\Console\Command\Server\CliServerCommand',
            'App\Console\Command\Server\WorkermanServerCommand',
        ];
    }
}
