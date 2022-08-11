<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

return [
    'default' => 'file',
    'stores'  => [
        'file'      => [
            'driver' => 'Max\Cache\Driver\FileDriver',
            'config' => [
                'path' => __DIR__ . '/../runtime/cache/app',
            ],
        ],
        'redis'     => [
            'driver' => 'Max\Cache\Driver\RedisDriver',
            'config' => [
                'connector' => 'Max\Redis\Connector\BaseConnector',
                'config'    => [],
            ],
        ],
        'memcached' => [
            'driver' => 'Max\Cache\Driver\MemcachedDriver',
            'config' => [
                'host' => '127.0.0.1', // 主机
                'port' => 11211,        // 端口
            ],
        ],
    ],
];
