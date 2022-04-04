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

use Max\Server\Http\Server;
use Swoole\Constant;

return [
    'servers'   => [
        [
            'name'      => 'websocket',
            'type'      => \Max\Server\Server::SERVER_WEBSOCKET,
            'host'      => '0.0.0.0',
            'port'      => 9501,
            'sockType'  => SWOOLE_SOCK_TCP,
            'settings'  => [
                Constant::OPTION_OPEN_WEBSOCKET_PROTOCOL => true,
            ],
            'callbacks' => [
                'open'    => [\Max\Server\WebSocket\Server::class, 'open'],
                'message' => [\Max\Server\WebSocket\Server::class, 'message'],
                'close'   => [\Max\Server\WebSocket\Server::class, 'close'],
                'receive' => [\Max\Server\WebSocket\Server::class, 'receive']
            ],
        ],
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
                'request' => [Server::class, 'request'],
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
    'callbacks' => [
        'start'        => [\Max\Server\Callbacks::class, 'start'],
        'workerStart'  => [\Max\Server\Callbacks::class, 'workerStart'],
        'task'         => [\Max\Server\Callbacks::class, 'task'],
        'finish'       => [\Max\Server\Callbacks::class, 'finish'],
        'managerStart' => [\Max\Server\Callbacks::class, 'managerStart'],
    ],
];
