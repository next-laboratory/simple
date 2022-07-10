<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use Swoole\Constant;

return [
    'swoole'=> [
        'port'    => env('APP_PORT', 9000), // 支持多端口监听,也可以只监听一个端口
        'binds'   => '0.0.0.0', // 绑定ip
        'settings'=> [
            Constant::OPTION_WORKER_NUM  => swoole_cpu_num(),
            Constant::OPTION_MAX_REQUEST => 100000,
        ],
    ],
];
