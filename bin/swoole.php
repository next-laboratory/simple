<?php

use App\Http\Kernel;
use Dotenv\Dotenv;
use Max\Aop\Scanner;
use Max\Aop\ScannerConfig;
use Max\Config\Repository;
use Max\Di\Context;
use Max\Event\Contracts\EventListenerInterface;
use Max\Event\EventDispatcher;
use Max\Event\ListenerCollector;
use Swoole\Constant;
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
        $listener = $container->make($listener);
        /** @var EventListenerInterface $listener */
        $listenerProvider->addListener($listener);
    }
    $server = new Server('0.0.0.0', 8989);
    $server->on('request', [Context::getContainer()->make(Kernel::class), 'handleSwooleRequest']);
    $server->set([
        Constant::OPTION_WORKER_NUM => 4,
    ]);
    echo <<<EOT
,--.   ,--.                  ,------. ,--.  ,--.,------.  
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. ' 
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' | 
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'  
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--' 

EOT;

    $server->start();
})();

