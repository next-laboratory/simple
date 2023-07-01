<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Console\Command\Server;

use App\Http\Kernel;
use App\Http\ServerRequest;
use Max\Di\Context;
use Max\Event\EventDispatcher;
use Max\Http\Server\Event\OnRequest;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! class_exists('React\Http\HttpServer')) {
            throw new \RuntimeException('You should install the react/react package before starting.');
        }

        $container       = Context::getContainer();
        $kernel          = $container->make(Kernel::class);
        $eventDispatcher = $container->make(EventDispatcher::class);
        $http            = new HttpServer(function (ServerRequestInterface $request) use ($kernel, $eventDispatcher) {
            $response = $kernel->handle($serverRequest = ServerRequest::createFromPsrRequest($request));
            $eventDispatcher->dispatch(new OnRequest($serverRequest, $response));
            return $response;
        });

        $listen = $this->host . ':' . $this->port;
        $socket = new SocketServer($listen);
        $this->showInfo();
        $http->listen($socket);

        return 0;
    }
}
