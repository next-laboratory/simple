<?php

use App\Bootstrap;
use App\Http\Kernel;
use App\Http\ServerRequest;
use Max\Di\Context;
use Max\HttpServer\ResponseEmitter\SwooleResponseEmitter;
use Swoole\Constant;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

(function() {
    $loader = require_once './vendor/autoload.php';
    Bootstrap::boot($loader, true);

    /**
     * Configuration.
     */
    $host     = '0.0.0.0';
    $port     = 8989;
    $settings = [
        Constant::OPTION_WORKER_NUM => 4,
    ];

    /**
     * Start server.
     */
    $server = new Server($host, $port);
    /** @var Kernel $kernel */
    $kernel = Context::getContainer()->make(Kernel::class);
    $server->on('request', function(Request $request, Response $response) use ($kernel) {
        $serverRequest = ServerRequest::createFromSwooleRequest($request);
        $psrResponse   = $kernel->createResponse($serverRequest);
        $serverRequest->withAttribute('rawRequest', $request);
        $serverRequest->withAttribute('rawResponse', $response);
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
    printf("PHP          Version:    %s\n", PHP_VERSION);
    printf("Swoole       Version:    %s\n", swoole_version());
    printf("Listen       Addr:       http://%s:%d\n", $host, $port);

    $server->start();
})();

