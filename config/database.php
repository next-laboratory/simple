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

use Max\Database\Connectors\AutoConnector;
use Max\Database\DatabaseConfig;

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'connector' => AutoConnector::class,
            'options' => [
                // 驱动
                DatabaseConfig::OPTION_DRIVER => 'mysql',
                // 主机地址
                DatabaseConfig::OPTION_HOST => env('database.host', 'localhost'),
                // 数据库用户名
                DatabaseConfig::OPTION_USER => env('database.user', 'user'),
                // unixSocket
                DatabaseConfig::OPTION_UNIX_SOCKET => null,
                // 数据库密码
                DatabaseConfig::OPTION_PASSWORD => env('database.pass', 'pass'),
                // 数据库名
                DatabaseConfig::OPTION_DB_NAME => env('database.dbname', 'name'),
                // 端口
                DatabaseConfig::OPTION_PORT => env('database.port', 3306),
                // 额外设置
                DatabaseConfig::OPTION_OPTIONS => [],
                // 编码
                DatabaseConfig::OPTION_CHARSET => env('database.charset', 'utf8mb4'),
                // 连接池内最大连接数量
                DatabaseConfig::OPTION_POOL_SIZE => 64,
            ],
        ],
    ],
];
