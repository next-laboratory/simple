<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

return [
    'aop'      => [
        'cache'      => false,
        'paths'      => [
            './app',
        ],
        'collectors' => [
            \App\Aop\Collector\RouteCollector::class,
            \App\Aop\Collector\ListenerCollector::class,
            \App\Aop\Collector\CommandCollector::class,
        ],
        'runtimeDir' => './runtime',
    ],
    'bindings' => [
        \Psr\EventDispatcher\EventDispatcherInterface::class      => \Max\Event\EventDispatcher::class,
        \Psr\EventDispatcher\ListenerProviderInterface::class     => \Max\Event\ListenerProvider::class,
        \Max\Http\Server\Contract\RouteDispatcherInterface::class => \Max\Http\Server\RouteDispatcher::class,
        \Max\Config\Contract\ConfigInterface::class               => \Max\Config\Repository::class,
        \Psr\Log\LoggerInterface::class                           => \App\Logger::class,
    ],
];
