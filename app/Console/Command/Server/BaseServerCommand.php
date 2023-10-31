<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Console\Command\Server;

use Symfony\Component\Console\Command\Command;

class BaseServerCommand extends Command
{
    protected string $host      = '0.0.0.0';

    protected int    $port      = 8989;

    protected string $container = 'unknown';

    protected function showInfo()
    {
        echo <<<'EOT'
,--.   ,--.                  ,------. ,--.  ,--.,------.
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. '
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' |
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--'

EOT;
        printf("System       Name:       %s\n", strtolower(PHP_OS));
        printf("Container    Name:       {$this->container}\n");
        printf("PHP          Version:    %s\n", PHP_VERSION);
        printf("Listen       Addr:       %s\n", $this->host . ':' . $this->port);
    }
}
