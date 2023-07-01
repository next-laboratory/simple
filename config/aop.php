<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

return [
    'scanDirs'   => [
        base_path('app'),
    ],
    'collectors' => [
        \Max\Event\ListenerCollector::class,
        \Max\Console\CommandCollector::class,
        \Max\Routing\RouteCollector::class,
        \Max\Aop\Collector\AspectCollector::class,
        \Max\Aop\Collector\PropertyAttributeCollector::class,
    ],
    'runtimeDir' => base_path('runtime/aop/'),
];
