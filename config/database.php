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
return [
    'default'     => 'mysql',
    'connections' => [
        'mysql' => [
            // 驱动
            'driver'     => 'mysql',
            // 主机地址
            'host'       => env('database.host', 'localhost'),
            // 数据库用户名
            'user'       => env('database.user', 'user'),
            // unixSocket
            'unixSocket' => null,
            // 数据库密码
            'password'   => env('database.pass', 'pass'),
            // 数据库名
            'database'   => env('database.dbname', 'name'),
            // 端口
            'port'       => env('database.port', 3306),
            // 额外设置
            'options'    => [],
            // 编码
            'charset'    => env('database.charset', 'utf8mb4'),
            // 连接池内最大连接数量
            'poolSize'   => 64,
        ],
    ],
];
