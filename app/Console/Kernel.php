<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Console;

use Next\Di\Context;
use Symfony\Component\Console\Application;

class Kernel extends Application
{
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
        $container = Context::getContainer();
        foreach ($this->commands() as $command) {
            $this->add($container->make($command));
        }
    }

    protected function commands(): array
    {
        return [
            'App\Console\Command\Internal\SwooleServerCommand',
            'App\Console\Command\Internal\SwooleCoServerCommand',
            'App\Console\Command\Internal\CliServerCommand',
            'App\Console\Command\Internal\WorkermanServerCommand',
            'App\Console\Command\Internal\ControllerMakeCommand',
            'App\Console\Command\Internal\MiddlewareMakeCommand',
            'App\Console\Command\Internal\MiddlewareMakeCommand',
        ];
    }
}
