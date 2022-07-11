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
use Psr\Http\Message\ServerRequestInterface;
use React\Http\HttpServer;
use React\Socket\SocketServer;

require_once __DIR__ . DIRECTORY_SEPARATOR.'base.php';

(function () {
    $loader = require './vendor/autoload.php';
    if (! class_exists('React\Http\HttpServer')) {
        throw new Exception('You should install the react/react package before starting.');
    }
    Bootstrap::boot($loader, true);

    $kernel = make(Kernel::class);
    $http   = new HttpServer(function (ServerRequestInterface $request) use ($kernel) {
        try {
            return $kernel->through(ServerRequest::createFromPsrRequest($request));
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
