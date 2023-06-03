<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Console\Command\Server;

use App\Http\ServerRequest;
use Exception;
use Max\Aop\Aop;
use Max\Di\Context;
use Max\Event\EventDispatcher;
use Max\Http\Server\Contract\HttpKernelInterface;
use Max\Http\Server\Event\OnRequest;
use Max\Http\Server\ResponseEmitter\SwooleResponseEmitter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use RuntimeException;
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!class_exists('Swoole\Server')) {
            throw new RuntimeException('You should install the swoole extension before starting.');
        }

        $settings = [
            Constant::OPTION_WORKER_NUM => swoole_cpu_num(),
            Constant::OPTION_MAX_REQUEST => 100000,
        ];

        // Start server
        $server = new Server($this->host, $this->port);
        $container = Context::getContainer();
        $kernel = $container->make(HttpKernelInterface::class);
        $eventDispatcher = $container->make(EventDispatcher::class);
        $server->on('request', function (Request $request, Response $response) use ($kernel, $eventDispatcher) {
            $psrRequest = ServerRequest::createFromSwooleRequest($request, [
                'request' => $request,
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
