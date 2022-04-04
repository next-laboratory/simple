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
    'default'     => 'redis',     // 默认连接
    'sleep'       => 0.4,           // 异常时候等待时长/秒
    'connections' => [
        'redis' => [
            'driver' => 'Max\Queue\Queues\Redis',
            'config' => [
                'host'     => '127.0.0.1',
                'port'     => 6379,
                'pass'     => '',
                'database' => 1,
            ],
        ]
    ],
];
