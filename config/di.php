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
        'Max\HttpServer\Contracts\ExceptionHandlerInterface' => 'Max\HttpServer\ExceptionHandler',
        'Psr\EventDispatcher\EventDispatcherInterface'       => 'Max\Event\EventDispatcher',
    ],
];
