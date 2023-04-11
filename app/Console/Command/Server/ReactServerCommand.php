<?php

namespace App\Console\Command\Server;

use Exception;
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!class_exists('React\Http\HttpServer')) {
            throw new Exception('You should install the react/react package before starting.');
        }

        (function () {

            $kernel = make(Kernel::class);
            $http   = new HttpServer(function (ServerRequestInterface $request) use ($kernel) {
                try {
                    return $kernel->handle(ServerRequest::createFromPsrRequest($request));
                } catch (Throwable $throwable) {
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
