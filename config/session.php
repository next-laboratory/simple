<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

return [
    'handler' => \Next\Session\Handler\FileHandler::class,
    'options' => [
        'gcDivisor'     => 100,
        'gcProbability' => 1,
        'gcMaxLifetime' => 1440,
        'path'          => base_path('runtime/framework/session'),
    ],
    //    'handler' => \Next\Session\Handler\RedisHandler::class,
    //    'options' => [
    //        'host'          => '127.0.0.1',
    //        'port'          => 6379,
    //        'timeout'       => 0,
    //        'persistentId'  => null,
    //        'retryInterval' => 0,
    //        'readTimeout'   => 0,
    //        'context'       => [],
    //        'password'      => '',
    //        'database'      => 0,
    //        'sessionPrefix' => (string) env('APP_NAME', 'APP'),
    //        'sessionTTL'    => 3600,
    //    ],
];
