<?php

return [
    'scanDirs'   => [
        base_path('app'),
    ],
    'collectors' => [
        \Max\Event\ListenerCollector::class,
        \Max\Routing\RouteCollector::class,
        \Max\Aop\Collector\AspectCollector::class,
        \Max\Aop\Collector\PropertyAttributeCollector::class
    ],
    'runtimeDir' => base_path('runtime/aop/'),
];