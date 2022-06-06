<?php

use App\Bootstrap;
use App\Http\Kernel;
use App\Http\ServerRequest;
use Max\Di\Context;
use Max\HttpServer\ResponseEmitter\WorkermanResponseEmitter;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

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
    $protocol  = 'http://0.0.0.0:8989';
    $workerNum = 4;

    /**
     * Start server.
     */
    $worker            = new Worker($protocol);
    $kernel            = Context::getContainer()->make(Kernel::class);
    $worker->onMessage = function(TcpConnection $connection, Request $request) use ($kernel) {
        $serverRequest = ServerRequest::createFromWorkermanRequest($request);
        $psrResponse   = $kernel->createResponse($serverRequest);
        $serverRequest->withAttribute('rawRequest', $request);
        $serverRequest->withAttribute('rawResponse', $connection);
        (new WorkermanResponseEmitter())->emit($psrResponse, $connection);
    };
    $worker->count     = $workerNum;

    echo <<<EOT
,--.   ,--.                  ,------. ,--.  ,--.,------.  
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. ' 
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' | 
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'  
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--' 

EOT;

    Worker::runAll();
})();

