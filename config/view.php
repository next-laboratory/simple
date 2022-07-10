<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

return [
    'default' => 'blade',
    'engines' => [
        'blade' => [
            'engine'  => 'Max\View\Engines\Blade',
            'options' => [
                // 模板目录
                'path'       => __DIR__ . '/../views/',
                // 编译和缓存目录
                'compileDir' => __DIR__ . '/../runtime/cache/views/',
                // 模板缓存
                'cache'      => false,
                // 模板后缀
                'suffix'     => '.blade.php',
            ],
        ],
    ],
];
