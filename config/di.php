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
        'scanDirs'   => [
            './app',
        ],
        'collectors' => [
            \Max\Aop\Collector\AspectCollector::class,
            \Max\Aop\Collector\PropertyAttributeCollector::class,
            \Max\Routing\RouteCollector::class,
            \Max\Event\ListenerCollector::class,
            \Max\Console\CommandCollector::class,
        ],
        'runtimeDir' => './runtime/framework/aop',
    ],
    'bindings' => [
        \Psr\Log\LoggerInterface::class => \App\Logger::class,
    ],
];
