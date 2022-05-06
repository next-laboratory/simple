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
    'scanner'  => [
        'cache'      => false,
        'paths'      => [
            BASE_PATH . 'app',
        ],
        'collectors' => [
            'Max\Event\ListenerCollector',
            'Max\Http\RouteCollector'
        ],
        'runtimeDir' => BASE_PATH . 'runtime',
    ],
    // 依赖绑定
    'bindings' => [
        'Psr\Http\Server\RequestHandlerInterface' => 'App\\Http\\Kernel',
    ],
];
