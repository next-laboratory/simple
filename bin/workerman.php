<?php

use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

$loader = require_once './vendor/autoload.php';

\Max\Aop\Scanner::init($loader, new \Max\Aop\ScannerConfig([
    'paths'      => ['./app'],
    'runtimeDir' => './runtime'
]));

\Max\Di\Context::getContainer()->bind(\Max\HttpServer\Contracts\ExceptionHandlerInterface::class, \Max\HttpServer\ExceptionHandler::class);

$worker = new Worker('http://0.0.0.0:8989');

$worker->onMessage = function(TcpConnection $tcpConnection, Request $request) {
    $requestHandler = \Max\Di\Context::getContainer()->make(\App\Kernel::class);
    $requestHandler->handleWorkermanRequest($tcpConnection, $request);
};

Worker::runAll();

