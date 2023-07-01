<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

return [
    'handler' => \Max\Session\Handler\FileHandler::class,
    'options' => [
        'gcDivisor'     => 100,
        'gcProbability' => 1,
        'gcMaxLifetime' => 1440,
        'path'          => base_path('runtime/framework/session'),
    ],
    //    'handler' => \Max\Session\Handler\RedisHandler::class,
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
