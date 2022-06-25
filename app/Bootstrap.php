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
use Psr\Log\LoggerInterface;
use ReflectionException;

class Bootstrap
{
    /**
     * @param ClassLoader $loader
     * @param bool        $enable
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
        if (file_exists(base_path('.env'))) {
            Dotenv::createUnsafeImmutable(BASE_PATH)->load();
        }

        $repository = $container->make(Repository::class);
        $repository->scan(base_path('./config'));

        /**
         * @var LoggerInterface $logger
         * Initialize loggers.
         */
        $logger = $container->make(Logger::class);
        if ('cli' === PHP_SAPI) {
            $logger->debug('Server started.');
        }
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
        $listeners        = $repository->get('listeners');
        foreach (array_unique(array_merge(ListenerCollector::getListeners(), $listeners)) as $listener) {
            $listenerProvider->addListener($container->make($listener));
        }
    }
}
