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

use Max\Swoole\Http\Server as HttpServer;
use Max\Server\Callbacks;
use Max\Server\Listeners\ServerListener;
use Max\Server\Server;
use Swoole\Constant;

return [
    'servers'   => [
        [
            'name'      => 'http',
            'type'      => Server::SERVER_HTTP,
            'host'      => '0.0.0.0',
            'port'      => 8989,
            'sockType'  => SWOOLE_SOCK_TCP,
            'settings'  => [
                Constant::OPTION_OPEN_HTTP_PROTOCOL => true,
            ],
            'callbacks' => [
                ServerListener::EVENT_REQUEST => [HttpServer::class, 'onRequest'],
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
        ServerListener::EVENT_TASK   => [Callbacks::class, 'onTask'],
        ServerListener::EVENT_FINISH => [Callbacks::class, 'onFinish'],
    ],
];
