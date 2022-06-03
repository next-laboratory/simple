<?php

return [
    'aop'      => [
        'cache'      => false,
        'paths'      => [
            './app'
        ],
        'collectors' => [
            'Max\HttpServer\RouteCollector',
        ],
        'runtimeDir' => './runtime',
    ],
    'bindings' => [
        'Max\HttpServer\Contracts\ExceptionHandlerInterface' => 'App\Exceptions\ExceptionHandler',
        'Psr\EventDispatcher\EventDispatcherInterface'       => 'Max\Event\EventDispatcher',
        'Max\Config\Contracts\ConfigInterface'               => 'Max\Config\Repository',
    ],
];
