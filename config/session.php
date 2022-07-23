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
        'file'  => [
            'handler' => 'Max\Session\Handlers\FileHandler',
            'options' => [
                'path'          => __DIR__ . '/../runtime/session',
                'gcDivisor'     => 100,
                'gcProbability' => 1,
                'gcMaxLifetime' => 1440,
            ],
        ],
        'redis' => [
            'handler' => 'Max\Session\Handlers\RedisHandler',
            'options' => [
                'connector' => 'Max\Redis\Connectors\BaseConnector',
                'host'      => '127.0.0.1',
                'port'      => 6379,
                'expire'    => 3600,
            ],
        ],
    ],
];
