<?php

$loader = require_once './vendor/autoload.php';

\Max\Aop\Scanner::init($loader, new \Max\Aop\ScannerConfig([
    'paths'      => ['./app'],
    'collectors' => [\Max\HttpServer\RouteCollector::class],
    'runtimeDir' => './runtime'
]));

$server = new \Swoole\Http\Server('0.0.0.0', 8989);

\Max\Di\Context::getContainer()->bind(\Max\HttpServer\Contracts\ExceptionHandlerInterface::class, \Max\HttpServer\ExceptionHandler::class);

$server->on('request', function(\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
    $requestHandler = \Max\Di\Context::getContainer()->make(\App\Kernel::class);
    $requestHandler->handleSwooleRequest($request, $response);
});

$server->start();
