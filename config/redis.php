<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use Max\Redis\RedisConfig;

return [
    'default'     => 'redis',
    'connections' => [
        'redis' => [
            'connector' => \Max\Redis\Connectors\BaseConnector::class,
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
            ],
        ],
    ],
];
