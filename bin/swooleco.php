<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use App\Http\Kernel;
use App\Http\ServerRequest;
use Max\Di\Context;
use Max\Http\Server\ResponseEmitter\SwooleResponseEmitter;
use Swoole\Constant;
use Swoole\Http\Request;
use Swoole\Http\Response;
use function Swoole\Coroutine\run;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

require_once __DIR__ . '/../app/bootstrap.php';

if (!class_exists('Swoole\Server')) {
    throw new Exception('You should install the swoole extension before starting.');
}

(function () {
    run(function () {
        // Configuration.
        $host     = '0.0.0.0';
        $port     = 8989;
        $settings = [
            Constant::OPTION_WORKER_NUM  => swoole_cpu_num(),
            Constant::OPTION_MAX_REQUEST => 100000,
        ];

        // Start server.
        $server = new Swoole\Coroutine\Http\Server($host, $port);
        $kernel = Context::getContainer()->make(Kernel::class);
        $server->handle('/', function (Request $request, Response $response) use ($kernel) {
            $psrResponse = $kernel->handle(ServerRequest::createFromSwooleRequest($request, [
                'request'  => $request,
                'response' => $response,
            ]));
            (new SwooleResponseEmitter())->emit($psrResponse, $response);
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
    });
})();
