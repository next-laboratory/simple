<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Console\Command\Internal;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CliServerCommand extends BaseServerCommand
{
    protected string $container = 'cli';

    protected function configure()
    {
        $this->setName('serve:cli')
             ->setDescription('Start CLI server');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->showInfo();
        passthru(PHP_BINARY . ' -S ' . $this->host . ':' . $this->port . ' -t ' . public_path() . ' ./server.php');
        return 0;
    }
}
