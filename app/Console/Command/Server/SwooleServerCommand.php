<?php

namespace App\Console\Command\Server;

use App\Http\ServerRequest;
use Exception;
use Max\Di\Context;
use Max\Event\EventDispatcher;
use Max\Http\Server\Contract\HttpKernelInterface;
use Max\Http\Server\Event\OnRequest;
use Max\Http\Server\ResponseEmitter\SwooleResponseEmitter;
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
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!class_exists('Swoole\Server')) {
            throw new Exception('You should install the swoole extension before starting.');
        }
        (function () {
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
                $psrRequest  = ServerRequest::createFromSwooleRequest($request, [
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
        })();

        return 0;
    }
}
