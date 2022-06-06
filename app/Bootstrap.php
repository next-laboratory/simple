<?php

namespace App;

use Composer\Autoload\ClassLoader;
use Dotenv\Dotenv;
use Max\Aop\Scanner;
use Max\Aop\ScannerConfig;
use Max\Config\Repository;
use Max\Di\Context;
use Max\Event\ListenerCollector;
use Max\Event\ListenerProvider;
use Psr\Container\ContainerExceptionInterface;
use ReflectionException;

class Bootstrap
{
    /**
     * @param ClassLoader $loader
     * @param bool $enable
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public static function boot(ClassLoader $loader, bool $enable = false): void
    {
        $container = Context::getContainer();

        /**
         * Initialize environment variables and configurations.
         *
         * @var Repository $repository
         */
        if (file_exists(BASE_PATH . '.env')) {
            Dotenv::createImmutable(BASE_PATH)->load();
        }
        $repository = $container->make(Repository::class);
        $repository->scan(BASE_PATH . './config');

        /**
         * Initialize scanner if it is enabled.
         */
        if ($enable) {
            Scanner::init($loader, new ScannerConfig($repository->get('di.aop')));
        }

        /**
         * Initialize bindings.
         */
        foreach ($repository->get('di.bindings') as $id => $value) {
            $container->bind($id, $value);
        }

        /**
         * Initialize event listeners.
         *
         * @var ListenerProvider $listenerProvider
         */
        $listenerProvider = $container->make(ListenerProvider::class);
        foreach (ListenerCollector::getListeners() as $listener) {
            $listenerProvider->addListener($container->make($listener));
        }
    }
}
