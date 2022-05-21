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

use Max\Redis\Connectors\AutoConnector;
use Max\Redis\RedisConfig;

return [
    'default'     => 'redis',
    'connections' => [
        'redis'   => [
            'connector' => AutoConnector::class,
            'options'   => [
                RedisConfig::OPTION_HOST           => '127.0.0.1',
                RedisConfig::OPTION_PORT           => 6379,
                RedisConfig::OPTION_AUTH           => '',
                RedisConfig::OPTION_DATABASE       => 0,
                RedisConfig::OPTION_TIMEOUT        => 3,
                RedisConfig::OPTION_READ_TIMEOUT   => 3,
                RedisConfig::OPTION_RETRY_INTERVAL => 3,
                RedisConfig::OPTION_RESERVED       => '',
                RedisConfig::OPTION_POOL_SIZE      => 64,
                RedisConfig::OPTION_AUTO_FILL      => true,
            ],
        ],
        'session' => [
            'connector' => AutoConnector::class,
            'options'   => [
                RedisConfig::OPTION_HOST           => '127.0.0.1',
                RedisConfig::OPTION_PORT           => 6379,
                RedisConfig::OPTION_AUTH           => '',
                RedisConfig::OPTION_DATABASE       => 1,
                RedisConfig::OPTION_TIMEOUT        => 3,
                RedisConfig::OPTION_READ_TIMEOUT   => 3,
                RedisConfig::OPTION_RETRY_INTERVAL => 3,
                RedisConfig::OPTION_RESERVED       => '',
                RedisConfig::OPTION_POOL_SIZE      => 64,
            ],
        ]
    ]
];
