<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App;

use Max\Aop\Scanner;
use Max\Aop\ScannerConfig;
use Max\Config\Repository;
use Max\Database\DBConfig;
use Max\Database\Manager;
use Max\Di\Context;
use Max\Event\EventDispatcher;
use Max\Event\ListenerProvider;
use Psr\Container\ContainerExceptionInterface;
use ReflectionException;

use function putenv;

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
        if (file_exists($envFile = base_path('.env'))) {
            $variables = parse_ini_file($envFile, false, INI_SCANNER_RAW);
            foreach ($variables as $key => $value) {
                putenv(sprintf('%s=%s', $key, $value));
            }
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
        if (! empty($listeners = $repository->get('listeners', []))) {
            foreach ($listeners as $listener) {
                $listenerProvider->addListener($container->make($listener));
            }
        }

        // Initialize database.
        $database = $repository->get('database');
        $manager  = $container->make(Manager::class);
        $manager->setDefault($database['default']);
        foreach ($database['connections'] as $name => $config) {
            $connector = $config['connector'];
            $options   = $config['options'];
            $manager->addConnector($name, new $connector(new DBConfig($options)));
        }
        $manager->setEventDispatcher($container->make(EventDispatcher::class));
        $manager->bootEloquent();
    }
}
