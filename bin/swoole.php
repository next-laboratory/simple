<?php

use App\Kernel;
use Dotenv\Dotenv;
use Max\Aop\Scanner;
use Max\Aop\ScannerConfig;
use Max\Config\Repository;
use Max\Di\Context;
use Max\Event\EventDispatcher;
use Max\Event\ListenerCollector;
use Max\HttpServer\Contracts\ExceptionHandlerInterface;
use Max\HttpServer\ExceptionHandler;
use Max\HttpServer\RouteCollector;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');

(function() {
    $loader = require_once './vendor/autoload.php';
    Dotenv::createImmutable(dirname(__DIR__))->load();
    $container = Context::getContainer();
    /** @var Repository $repository */
    $repository = $container->make(Repository::class);
    $repository->scan('./config');
    Scanner::init($loader, new ScannerConfig($repository->get('di.aop')));
    foreach ($repository->get('di.bindings') as $id => $value) {
        $container->bind($id, $value);
    }
    /** @var EventDispatcher $eventDispatcher */
    $eventDispatcher  = $container->make(EventDispatcher::class);
    $listenerProvider = $eventDispatcher->getListenerProvider();
    foreach (ListenerCollector::getListeners() as $listener) {
        $listenerProvider->addListener($container->make($listener));
    }
    $server = new Server('0.0.0.0', 8989);
    $server->on('request', function(Request $request, Response $response) {
        $requestHandler = Context::getContainer()->make(Kernel::class);
        $requestHandler->handleSwooleRequest($request, $response);
    });

    echo <<<EOT
,--.   ,--.                  ,------. ,--.  ,--.,------.  
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. ' 
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' | 
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'  
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--' 

EOT;

    $server->start();
})();

