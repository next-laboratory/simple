<?php

namespace App\Console\Command\Server;

use Symfony\Component\Console\Command\Command;
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
