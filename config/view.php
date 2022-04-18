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
    'default' => 'blade',
    'engines' => [
        'blade' => [
            'engine'  => 'Max\View\Engines\Blade',
            'options' => [
                // 模板目录
                'path'       => __DIR__ . '/../storage/views/',
                // 编译和缓存目录
                'compileDir' => __DIR__ . '/../runtime/cache/views/',
                // 模板缓存
                'cache'      => false,
                // 模板后缀
                'suffix'     => '.blade.php',
            ],
        ],
    ]
];
