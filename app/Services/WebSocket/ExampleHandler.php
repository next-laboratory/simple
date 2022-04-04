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

namespace App\Services\WebSocket;

use Max\Server\Contracts\WebSocketHandlerInterface;
use Max\Server\WebSocket\Annotations\WebSocketHandler;
use Swoole\Http\Request;
use Swoole\Table;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

#[WebSocketHandler(path: '/')]
class ExampleHandler implements WebSocketHandlerInterface
{
    /**
     * @var Table
     */
    protected Table $table;

    public function __construct()
    {
        $table = new Table(1 << 10);
        $table->column('uid', Table::TYPE_INT);
        $table->create();
        $this->table = $table;
    }

    /**
     * @param Server  $server
     * @param Request $request
     */
    public function open(Server $server, Request $request)
    {
        if (isset($request->get['id'])) {
            $this->table->set((string)$request->fd, ['uid' => $request->get['id']]);
        }
    }

    /**
     * @param Server $server
     * @param Frame  $frame
     */
    public function message(Server $server, Frame $frame)
    {
        $uid = $this->table->get((string)$frame->fd, 'uid');
        switch ($frame->data) {
            case 'ping':
                $server->push($frame->fd, 'pong');
                break;
            default:
                $server->push($frame->fd, $uid);
        }
    }

    /**
     * @param Server $server
     * @param        $fd
     */
    public function close(Server $server, $fd)
    {
        $this->table->del((string)$fd);
    }
}
