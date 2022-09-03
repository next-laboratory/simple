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
            \Max\Http\Server\RouteCollector::class,
            \Max\Event\ListenerCollector::class,
            \Max\Console\CommandCollector::class,
        ],
        'runtimeDir' => './runtime',
    ],
    'bindings' => [
        \Psr\EventDispatcher\EventDispatcherInterface::class    => \Max\Event\EventDispatcher::class,
        \Max\Http\Server\Contract\RouteResolverInterface::class => \Max\Http\Server\RouteResolver::class,
        \Max\Config\Contract\ConfigInterface::class             => \Max\Config\Repository::class,
        \Psr\Log\LoggerInterface::class                         => \App\Logger::class,
    ],
];
