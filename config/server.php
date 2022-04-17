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

use Swoole\Constant;

return [
    'servers'   => [
        [
            'name'      => 'http',
            'type'      => \Max\Server\Server::SERVER_HTTP,
            'host'      => '0.0.0.0',
            'port'      => 8080,
            'sockType'  => SWOOLE_SOCK_TCP,
            'settings'  => [
                Constant::OPTION_OPEN_HTTP_PROTOCOL => true,
            ],
            'callbacks' => [
                'request' => [\Max\Http\Server::class, 'onRequest'],
                'task'         => [\Max\Server\Callbacks::class, 'task'],
                'finish'       => [\Max\Server\Callbacks::class, 'finish'],
            ],
        ],
    ],
    'mode'      => SWOOLE_PROCESS,
    'settings'  => [
        Constant::OPTION_ENABLE_COROUTINE      => true,
        Constant::OPTION_TASK_WORKER_NUM       => 2,
        Constant::OPTION_WORKER_NUM            => 2,
        Constant::OPTION_TASK_ENABLE_COROUTINE => true,
    ],
];
