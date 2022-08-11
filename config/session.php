<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

return [
    'handler' => 'Max\Session\Handler\FileHandler',
    'options' => [
        'path'          => __DIR__ . '/../runtime/session',
        'gcDivisor'     => 100,
        'gcProbability' => 1,
        'gcMaxLifetime' => 1440,
    ],
    //    'handler' => 'Max\Session\Handler\RedisHandler',
    //    'options' => [
    //        'connector' => 'Max\Redis\Connector\BaseConnector',
    //        'host'      => '127.0.0.1',
    //        'port'      => 6379,
    //        'expire'    => 3600,
    //    ],
];
