<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App;

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
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public static function boot(bool $enable = false): void
    {
        $container = Context::getContainer();

        // Initialize environment variables and configurations
        if (file_exists(base_path('.env'))) {
            Dotenv::createUnsafeImmutable(BASE_PATH)->load();
        }
        $repository = $container->make(Repository::class);
        $repository->scan(base_path('./config'));

        // Initialize scanner if it is enabled
        if ($enable) {
            Scanner::init(new ScannerConfig($repository->get('di.aop')));
        }

        // Initialize bindings
        foreach ($repository->get('di.bindings') as $id => $value) {
            $container->bind($id, $value);
        }

        // Initialize event listeners
        $listenerProvider = $container->make(ListenerProvider::class);
        if (!empty($listeners = $repository->get('listeners', []))) {
            foreach ($listeners as $listener) {
                $listenerProvider->addListener($container->make($listener));
            }
        }
    }
}
