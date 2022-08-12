<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use Max\Database\Connector\AutoConnector;
use Max\Database\DatabaseConfig;

return [
    'default'     => env('DB_DEFAULT', 'mysql'),
    'connections' => [
        'mysql' => [
            'connector' => AutoConnector::class,
            'options'   => [
                // 驱动
                DatabaseConfig::OPTION_DRIVER      => 'mysql',
                // 主机地址
                DatabaseConfig::OPTION_HOST        => env('DB_HOST', 'localhost'),
                // 数据库用户名
                DatabaseConfig::OPTION_USER        => env('DB_USER', 'user'),
                // unixSocket
                DatabaseConfig::OPTION_UNIX_SOCKET => null,
                // 数据库密码
                DatabaseConfig::OPTION_PASSWORD    => env('DB_PASS', 'pass'),
                // 数据库名
                DatabaseConfig::OPTION_DB_NAME     => env('DB_NAME', 'name'),
                // 端口
                DatabaseConfig::OPTION_PORT        => env('DB_PORT', 3306),
                // 额外设置
                DatabaseConfig::OPTION_OPTIONS     => [],
                // 编码
                DatabaseConfig::OPTION_CHARSET     => env('DB_CHARSET', 'utf8mb4'),
                // 连接池内最大连接数量
                DatabaseConfig::OPTION_POOL_SIZE   => 64,
            ],
        ],
    ],
];
