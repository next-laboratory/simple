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

use Max\Aop\Scanner;
use Max\Config\Repository;
use Max\Di\Context;
use Max\Event\EventDispatcher;
use Max\Event\ListenerCollector;
use Max\Framework\Console\CommandCollector;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
const BASE_PATH = __DIR__ . '/../';

(function() {
    $loader = require_once BASE_PATH . 'vendor/autoload.php';
    (new Dotenv())->load('./.env');
    $container = Context::getContainer();
    /** @var Repository $repository */
    $repository = $container->make(Repository::class);
    $repository->scan(base_path('config'));
    $bindings = $repository->get('di.bindings', []);

    Scanner::init($loader, $repository->get('di.scanner'));

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
        $container->bind($id, $binding);
    }

    /** @var EventDispatcher $eventDispatcher */
    $eventDispatcher  = $container->make(EventDispatcher::class);
    $listenerProvider = $eventDispatcher->getListenerProvider();
    foreach (ListenerCollector::getListeners() as $listener) {
        $listenerProvider->addListener($container->make($listener));
    }

    $application = new Application();

    foreach ($config['commands'] ?? [] as $command) {
        $application->add(new $command);
    }
    foreach (CommandCollector::all() as $command) {
        $application->add(new $command);
    }

    $application->run();
})();


