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

use Monolog\Logger;

return [
    'default' => 'app',
    'logger'  => [
        'app' => [
            'handler' => 'Monolog\Handler\RotatingFileHandler',
            'options' => [
                'filename' => __DIR__ . '/../runtime/logs/app.log',
                'maxFiles' => 180,
                'level'    => Logger::DEBUG,
            ],
        ],
        'sql' => [
            'handler' => 'Monolog\Handler\RotatingFileHandler',
            'options' => [
                'filename' => __DIR__ . '/../runtime/logs/database/sql.log',
                'maxFiles' => 180,
                'level'    => Logger::DEBUG,
            ],
        ],
    ],
];
