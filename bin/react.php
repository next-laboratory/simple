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
use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Socket\SocketServer;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

require_once __DIR__ . '/../app/bootstrap.php';

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

    $listen = '0.0.0.0:8989';
    $socket = new SocketServer($listen);

    echo <<<'EOT'
,--.   ,--.                  ,------. ,--.  ,--.,------.
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. '
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' |
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--'

EOT;
    printf("System       Name:       %s\n", strtolower(PHP_OS));
    printf("Container    Name:       ReactPHP\n");
    printf("PHP          Version:    %s\n", PHP_VERSION);
    printf("Listen       Addr:       http://%s\n", $listen);
    $http->listen($socket);
})();
