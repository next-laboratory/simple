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
    'default' => 'file',
    'stores'  => [
        'file'  => [
            'handler' => 'Max\Session\Handlers\File',
            'options' => [
                'path'          => __DIR__ . '/../runtime/session',
                'gcDivisor'     => 100,
                'gcProbability' => 1,
                'gcMaxLifetime' => 1440,
            ],
        ],
        'cache' => [
            'handler' => 'Max\Session\Handlers\Cache',
            'options' => [
                'ttl' => 3600,
            ]
        ]
    ],
];
