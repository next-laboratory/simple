<?php

use App\Bootstrap;
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

(function() {
    $loader = require './vendor/autoload.php';
    if (!class_exists('React\Http\HttpServer')) {
        throw new Exception('You should install the react/react extension before starting.');
    }
    Bootstrap::boot($loader, true);

    $kernel = make(Kernel::class);
    $http   = new HttpServer(function(ServerRequestInterface $request) use ($kernel) {
        try {
            return $kernel->through(ServerRequest::createFromPsrRequest($request));
        } catch (Throwable $throwable) {
            dump($throwable);
        }
    });

    $listen = '0.0.0.0:8989';
    $socket = new SocketServer($listen);

    echo <<<EOT
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


