<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Console;

use Exception;
use Max\Console\CommandCollector;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\Console\Application;

use function App\config;

class Kernel
{
    /**
     * 注册命令.
     *
     * @var array<int, string> $commands
     */
    protected array $commands = [];

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function run(): void
    {
        $application = new Application('MaxPHP', 'dev');
        $commands    = array_merge($this->commands, CommandCollector::all(), config('config.commands', []));
        foreach ($commands as $command) {
            $application->add(make($command));
        }
        $application->run();
    }
}
