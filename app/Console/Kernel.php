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
use Max\Aop\Scanner;
use Max\Console\CommandCollector;
use Symfony\Component\Console\Application;

class Kernel
{
    /**
     * 注册命令.
     */
    protected array $commands = [];

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $config      = Scanner::scanConfig(base_path('vendor/composer/installed.json'));
        $application = new Application();
        $commands    = array_merge($this->commands, $config['commands'], CommandCollector::all());
        foreach ($commands as $command) {
            $application->add(new $command());
        }
        $application->run();
    }
}
