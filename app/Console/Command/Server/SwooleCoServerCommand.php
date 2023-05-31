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
use Max\Di\Context;
use Max\Event\EventDispatcher;
use Max\Http\Server\Event\OnRequest;
use Max\Http\Server\ResponseEmitter\SwooleResponseEmitter;
use Swoole\Constant;
use Swoole\Coroutine\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Swoole\Coroutine\run;

class SwooleCoServerCommand extends BaseServerCommand
{
    protected string $container = 'swoole-co';

    protected function configure()
    {
        $this->setName('serve:swoole-co')
            ->setDescription('Start swoole-co server');
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! class_exists('Swoole\Server')) {
            throw new \Exception('You should install the swoole extension before starting.');
        }

        (function () {
            run(function () {
                $settings = [
                    Constant::OPTION_WORKER_NUM  => swoole_cpu_num(),
                    Constant::OPTION_MAX_REQUEST => 100000,
                ];

                $container = Context::getContainer();
                // Start server.
                $server = new Server($this->host, $this->port);

                $kernel = $container->make(Kernel::class);
                $eventDispatcher = $container->make(EventDispatcher::class);
                $server->handle('/', function (Request $request, Response $response) use ($kernel, $eventDispatcher) {
                    $psrResponse = $kernel->handle($serverRequest = ServerRequest::createFromSwooleRequest($request, [
                        'request'  => $request,
                        'response' => $response,
                    ]));
                    (new SwooleResponseEmitter())->emit($psrResponse, $response);

                    $eventDispatcher->dispatch(new OnRequest($serverRequest, $psrResponse));
                });

                $server->set($settings);
                $this->showInfo();
                $server->start();
            });
        })();
        return 0;
    }
}
