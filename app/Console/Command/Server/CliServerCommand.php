<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Console\Command\Server;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CliServerCommand extends BaseServerCommand
{
    protected string $container = 'cli-server';

    protected function configure()
    {
        $this->setName('serve:cli-server')
            ->setDescription('Start cli-server');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->showInfo();
        passthru(PHP_BINARY . ' -S ' . $this->host . ':' . $this->port . ' -t ' . public_path() . ' ./server.php');
        return 0;
    }
}
