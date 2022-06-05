<?php

return [
    'aop'      => [
        'cache'      => false,
        'paths'      => [
            './app'
        ],
        'collectors' => [
            'Max\HttpServer\RouteCollector',
            'Max\Event\ListenerCollector'
        ],
        'runtimeDir' => './runtime',
    ],
    'bindings' => [
        'Psr\EventDispatcher\EventDispatcherInterface' => 'Max\Event\EventDispatcher',
        'Max\Config\Contracts\ConfigInterface'         => 'Max\Config\Repository',
        'Psr\Log\LoggerInterface'                      => 'Max\Log\Logger',
    ],
];
