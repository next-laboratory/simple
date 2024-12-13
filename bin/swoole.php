<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

use App\Middlewares\ExceptionHandleMiddleware;
use App\Middlewares\SwooleResponseEmitter;
use App\ServerRequest;
use Next\Http\Server\RequestHandler;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');
require_once BASE_PATH . 'app/bootstrap.php';

$host            = env('SERVER_HOST', '0.0.0.0');
$port            = (int)env('SERVER_PORT', 8989);
$server          = new Server($host, $port);
$routeDispatcher = require_once base_path('app/router.php');

$server->on('request', function (Request $request, Response $response) use ($routeDispatcher) {
    try {
        (new RequestHandler())
            ->withMiddleware(
                new SwooleResponseEmitter($response),
                new ExceptionHandleMiddleware(),
                $routeDispatcher
            )
            ->handle(ServerRequest::createFromSwooleRequest($request));
    } catch (\Throwable $e) {
        $response->end('Internal Server Error');
    }
});

echo <<<EOT
,--.  ,--.                   ,--.  ,------. ,--.  ,--.,------.
|  ,'.|  | ,---. ,--.  ,--.,-'  '-.|  .--. '|  '--'  ||  .--. '
|  |' '  || .-. : \  `'  / '-.  .-'|  '--' ||  .--.  ||  '--' |
|  | `   |\   --. /  /.  \   |  |  |  | --' |  |  |  ||  | --'
`--'  `--' `----''--'  '--'  `--'  `--'     `--'  `--'`--'

EOT;

printf("System       Name:       %s\n", strtolower(PHP_OS));
printf("PHP          Version:    %s\n", PHP_VERSION);
printf("Listen       Addr:       %s\n", $host . ':' . $port);
$server->start();
