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
            'Max\Http\Server\RouteCollector',
            'Max\Event\ListenerCollector',
            'Max\Console\CommandCollector',
        ],
        'runtimeDir' => './runtime',
    ],
    'bindings' => [
        'Psr\EventDispatcher\EventDispatcherInterface' => 'Max\Event\EventDispatcher',
        'Max\Config\Contract\ConfigInterface'          => 'Max\Config\Repository',
        'Psr\Log\LoggerInterface'                      => 'App\Logger',
    ],
];
