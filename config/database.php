<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use Max\Database\Connector\BaseConnector;
use Max\Database\DBConfig;

use function App\env;

return [
    'default'     => env('DB_DEFAULT', 'mysql'),
    'connections' => [
        'mysql' => [
            'connector' => BaseConnector::class,
            'options'   => [
                // 驱动
                DBConfig::OPTION_DRIVER      => 'mysql',
                // 主机地址
                DBConfig::OPTION_HOST        => env('DB_HOST', 'localhost'),
                // 数据库用户名
                DBConfig::OPTION_USER        => env('DB_USER', 'user'),
                // unixSocket
                DBConfig::OPTION_UNIX_SOCKET => null,
                // 数据库密码
                DBConfig::OPTION_PASSWORD    => env('DB_PASS', 'pass'),
                // 数据库名
                DBConfig::OPTION_DB_NAME     => env('DB_NAME', 'name'),
                // 端口
                DBConfig::OPTION_PORT        => env('DB_PORT', 3306),
                // 额外设置
                DBConfig::OPTION_OPTIONS     => [],
                // 编码
                DBConfig::OPTION_CHARSET     => env('DB_CHARSET', 'utf8mb4'),
                // 连接池内最大连接数量
                DBConfig::OPTION_POOL_SIZE   => 12,
            ]
        ],
    ],
];
