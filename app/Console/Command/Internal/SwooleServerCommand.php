<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Console\Command\Internal;

use App\Http\ServerRequest;
use Next\Di\Context;
use Next\Event\EventDispatcher;
use Next\Http\Server\Contract\HttpKernelInterface;
use Next\Http\Server\Event\OnRequest;
use Next\Http\Server\ResponseEmitter\SwooleResponseEmitter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Swoole\Constant;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SwooleServerCommand extends BaseServerCommand
{
    protected string $container = 'swoole';

    protected function configure()
    {
        $this->setName('serve:swoole')
            ->setDescription('Start swoole server');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! class_exists('Swoole\Server')) {
            throw new \RuntimeException('You should install the swoole extension before starting.');
        }

        $settings = [
            Constant::OPTION_WORKER_NUM  => swoole_cpu_num(),
            Constant::OPTION_MAX_REQUEST => 100000,
        ];

        // Start server
        $server          = new Server($this->host, $this->port);
        $container       = Context::getContainer();
        $kernel          = $container->make(HttpKernelInterface::class);
        $eventDispatcher = $container->make(EventDispatcher::class);
        $server->on('request', function (Request $request, Response $response) use ($kernel, $eventDispatcher) {
            $psrRequest = ServerRequest::createFromSwooleRequest($request, [
                'request'  => $request,
                'response' => $response,
            ]);
            $psrResponse = $kernel->handle($psrRequest);
            (new SwooleResponseEmitter())->emit($psrResponse, $response);
            $eventDispatcher->dispatch(new OnRequest($psrRequest, $psrResponse));
        });
        $server->set($settings);
        $this->showInfo();
        $server->start();

        return 0;
    }
}
