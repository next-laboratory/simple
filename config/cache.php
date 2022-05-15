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
    'default' => 'file',
    'stores'  => [
        //文件缓存
        'file'      => [
            'handler' => 'Max\Cache\Handlers\FileHandler',
            'options' => [
                'path' => __DIR__ . '/../runtime/cache',
            ],
        ],
        // redis缓存
        'redis'     => [
            'handler' => 'Max\Cache\Handlers\RedisHandler',
            'options' => [
                'connection' => 'redis',
            ],
        ],
        //memcached缓存
        'memcached' => [
            'handler' => 'Max\Cache\Handlers\MemcachedHandler',
            'options' => [
                'host' => '127.0.0.1', //主机
                'port' => 11211        //端口
            ],
        ]
    ],
];
