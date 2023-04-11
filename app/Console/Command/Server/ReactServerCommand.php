<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Console\Command\Server;

use App\Http\Kernel;
use App\Http\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReactServerCommand extends BaseServerCommand
{
    protected function configure()
    {
        $this->setName('serve:react')
            ->setDescription('Start ReactPHP server');
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! class_exists('React\Http\HttpServer')) {
            throw new \Exception('You should install the react/react package before starting.');
        }

        (function () {
            $kernel = make(Kernel::class);
            $http   = new HttpServer(function (ServerRequestInterface $request) use ($kernel) {
                try {
                    return $kernel->handle(ServerRequest::createFromPsrRequest($request));
                } catch (\Throwable $throwable) {
                    dump($throwable);
                }
            });

            $listen = $this->host . ':' . $this->port;
            $socket = new SocketServer($listen);
            $this->showInfo();
            $http->listen($socket);
        })();

        return 0;
    }
}
