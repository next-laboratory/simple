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
            \Max\Aop\Collector\PropertyAnnotationCollector::class,
            \App\Aop\Collector\RouteCollector::class,
            \App\Aop\Collector\ListenerCollector::class,
            \App\Aop\Collector\CommandCollector::class,
        ],
        'runtimeDir' => './runtime/aop',
    ],
    'bindings' => [
        \Psr\Log\LoggerInterface::class => \App\Logger::class,
    ],
];
