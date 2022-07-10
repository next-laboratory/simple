<?php

declare(strict_types=1);

/**
 * This file is part of the Max package.
 *
 * (c) Cheng Yao <987861463@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Bootstrap;
use App\Http\Kernel;
use App\Http\ServerRequest;
use Max\Di\Context;
use Max\Http\Server\ResponseEmitter\SwooleResponseEmitter;
use Swoole\Constant;
use Swoole\Http\Request;
use Swoole\Http\Response;
use function Swoole\Coroutine\run;

require_once __DIR__ . DIRECTORY_SEPARATOR.'base.php';

(function() {
    $loader = require_once './vendor/autoload.php';
    if (!class_exists('Swoole\Server')) {
        throw new Exception('You should install the swoole extension before starting.');
    }
    Bootstrap::boot($loader, true);

    run(function() {

        /**
         * Configuration.
         */
        $host     = '0.0.0.0';
        $port     = 8989;
        $settings = [
            Constant::OPTION_WORKER_NUM  => 4,
            Constant::OPTION_MAX_REQUEST => 100000,
        ];

        /**
         * Start server.
         */
        $server = new Swoole\Coroutine\Http\Server($host, $port);
        $kernel = Context::getContainer()->make(Kernel::class);;
        $server->handle('/', function(Request $request, Response $response) use ($kernel) {
            $psrResponse = $kernel->through(ServerRequest::createFromSwooleRequest($request, [
                'request'  => $request,
                'response' => $response,
            ]));
            (new SwooleResponseEmitter())->emit($psrResponse, $response);
        });

        $server->set($settings);
        echo <<<EOT
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
    });
})();

