#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * This file is part of the Max package.
 *
 * (c) Cheng Yao <987861463@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Max\Config\Repository;
use Max\Di\Container;
use Max\Di\Context;
use Max\Di\Scanner;
use Max\Env\Env;
use Max\Env\Loader\IniFileLoader;
use Max\Event\EventDispatcher;
use Max\Event\ListenerCollector;
use Max\Http\RouteCollector;
use Max\Server\Server as MaxSwooleServer;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
const BASE_PATH = __DIR__ . '/../';

(function($argv) {
    $loader = require './vendor/autoload.php';
    /** @var Container $container */
    $container = Context::getContainer();
    /** @var Env $env */
    $env = $container->make(Env::class);
    $env->load(new IniFileLoader('./.env'));
    /** @var Repository $repository */
    $repository = $container->make(Repository::class);
    $repository->load(glob(base_path('config/*.php')));
    $bindings = $repository->get('di.bindings', []);

    $installed = json_decode(file_get_contents(BASE_PATH . '/vendor/composer/installed.json'), true);
    $installed = $installed['packages'] ?? $installed;
    $config    = [];
    foreach ($installed as $package) {
        if (isset($package['extra']['max']['config'])) {
            $configProvider = $package['extra']['max']['config'];
            $configProvider = new $configProvider;
            if (method_exists($configProvider, '__invoke')) {
                if (is_array($configItem = $configProvider())) {
                    $config = array_merge_recursive($config, $configItem);
                }
            }
        }
    }
    $bindings = array_merge($config['bindings'] ?? [], $bindings);

    foreach ($bindings ?? [] as $id => $binding) {
        $container->alias($id, $binding);
    }
    Scanner::init($loader, $repository->get('di.scanner'));
    /** @var EventDispatcher $eventDispatcher */
    $eventDispatcher  = $container->make(EventDispatcher::class);
    $listenerProvider = $eventDispatcher->getListenerProvider();
    foreach (ListenerCollector::getListeners() as $listener) {
        $listenerProvider->addListener($container->make($listener));
    }
    echo 'PHP:' . PHP_VERSION . PHP_EOL;
    echo 'swoole:' . SWOOLE_VERSION . PHP_EOL;

    switch ($argv[1] ?? '') {
        case 'start':
            $server = new MaxSwooleServer($repository->get('server'), $eventDispatcher);
            $container->set(MaxSwooleServer::class, $server);
            $server->start();
            break;
        case 'stop':
            $pids = [
                '/var/run/max-php-manager.pid',
                '/var/run/max-php-master.pid',
            ];
            foreach ($pids as $pid) {
                if (!file_exists($pid)) {
                    throw new RuntimeException('服务没有运行');
                }
                posix_kill((int)file_get_contents($pid), SIGTERM);
                unlink($pid);
            }
            echo 'Server stopped!' . PHP_EOL;
            break;
        default:
            echo 'Please input action \'start\' or \'stop\'' . PHP_EOL;
    }
})($argv);




