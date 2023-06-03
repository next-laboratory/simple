<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Console;

use Max\Console\CommandCollector;
use Max\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @inheritDoc
     */
    protected function commands(): array
    {
        return [
            'App\Console\Command\Server\SwooleServerCommand',
            'App\Console\Command\Server\SwooleCoServerCommand',
            'App\Console\Command\Server\AmpServerCommand',
            'App\Console\Command\Server\CliServerCommand',
            'App\Console\Command\Server\ReactServerCommand',
            'App\Console\Command\Server\WorkermanServerCommand',
        ];
    }
}
