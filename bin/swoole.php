<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use App\Bootstrap;
use App\Http\Kernel;
use App\Http\ServerRequest;
use Max\Di\Context;
use Max\Http\Server\Event\OnRequest;
use Max\Http\Server\ResponseEmitter\SwooleResponseEmitter;
use Swoole\Constant;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

require_once __DIR__ . '/base.php';

(function() {
    if (!class_exists('Swoole\Server')) {
        throw new Exception('You should install the swoole extension before starting.');
    }
    Bootstrap::boot(true);

    // Configuration.
    $port     = 8989;
    $host     = '0.0.0.0';
    $settings = [
        Constant::OPTION_WORKER_NUM  => swoole_cpu_num(),
        Constant::OPTION_MAX_REQUEST => 100000,
    ];

    // Start server
    $server          = new Server($host, $port);
    $container       = Context::getContainer();
    $kernel          = $container->make(Kernel::class);
    $eventDispatcher = $container->make(\Max\Event\EventDispatcher::class);
    $server->on('request', function(Request $request, Response $response) use ($kernel, $eventDispatcher) {
        $psrRequest  = ServerRequest::createFromSwooleRequest($request, [
            'request'  => $request,
            'response' => $response,
        ]);
        $psrResponse = $kernel->handle($psrRequest);
        (new SwooleResponseEmitter())->emit($psrResponse, $response);
        $eventDispatcher->dispatch(new OnRequest($psrRequest, $psrResponse));
    });
    $server->set($settings);

    echo <<<'EOT'
,--.   ,--.                  ,------. ,--.  ,--.,------.
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. '
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' |
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--'

EOT;
    printf("System       Name:       %s\n", strtolower(PHP_OS));
    printf("Container    Name:       swoole\n");
    printf("PHP          Version:    %s\n", PHP_VERSION);
    printf("Swoole       Version:    %s\n", swoole_version());
    printf("Listen       Addr:       http://%s:%d\n", $host, $port);

    $server->start();
})();
