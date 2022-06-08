<?php

declare(strict_types=1);

/**
 * This file is part of the Max package.
 *
 * (c) Cheng Yao <987861463@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'aop'      => [
        'cache'      => false,
        'paths'      => [
            './app'
        ],
        'collectors' => [
            'Max\Http\Server\RouteCollector',
            'Max\Event\ListenerCollector',
            'Max\Framework\Console\CommandCollector',
        ],
        'runtimeDir' => './runtime',
    ],
    'bindings' => [
        'Psr\EventDispatcher\EventDispatcherInterface' => 'Max\Event\EventDispatcher',
        'Max\Config\Contracts\ConfigInterface'         => 'Max\Config\Repository',
        'Psr\Log\LoggerInterface'                      => 'Max\Log\Logger',
    ],
];
