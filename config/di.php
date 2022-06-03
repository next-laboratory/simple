<?php

return [
    'bindings' => [
        \Max\HttpServer\Contracts\ExceptionHandlerInterface::class => \Max\HttpServer\ExceptionHandler::class,
//        \Psr\EventDispatcher\EventDispatcherInterface::class       => \Max\Event\EventDispatcher::class,
    ],
];
