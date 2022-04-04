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

namespace App\Listeners;

use Max\Console\Output\ConsoleOutput;
use Max\Console\Output\Formatter;
use Max\Di\Annotations\Inject;
use Max\Event\Contracts\EventListenerInterface;
use Max\Foundation\Annotations\Listen;
use Max\Http\Exceptions\HttpException;
use Max\Server\Events\OnClose;
use Max\Server\Events\OnFinish;
use Max\Server\Events\OnMessage;
use Max\Server\Events\OnOpen;
use Max\Server\Events\OnRequest;
use Max\Server\Events\OnTask;

#[Listen]
class ServerListener implements EventListenerInterface
{
    /**
     * @var ConsoleOutput
     */
    #[Inject]
    protected ConsoleOutput $output;

    /**
     * @return iterable
     */
    public function listen(): iterable
    {
        return [
            OnTask::class,
            OnFinish::class,
            OnOpen::class,
            OnMessage::class,
            OnClose::class,
            OnRequest::class,
        ];
    }

    /**
     * @throws HttpException
     */
    public function process(object $event): void
    {
        echo gmdate('Y-m-d H:i:s') . '[' . str_pad($event::class, 27, ' ', STR_PAD_RIGHT) . ']';

        switch (true) {
            case $event instanceof OnOpen:
                echo 'PATH: ', $event->request->server['request_uri'], ' FD: ', $event->request->fd, PHP_EOL;
                break;
            case $event instanceof OnMessage:
                echo (new Formatter())->setForeground('blue')->apply($event->frame->data) . PHP_EOL;
                break;
            case $event instanceof OnClose:
                echo 'FD: ', $event->fd . PHP_EOL;
                break;
            case $event instanceof OnRequest:
                $response = $event->response;
                $request  = $event->request;
                $code     = $response->getStatusCode();
                $method   = $request->getMethod();
                $uri      = $request->getUri()->__toString();
                echo (new Formatter())->setForeground($code == 200 ? 'green' : 'red')->apply(str_pad((string)$code, 10, ' ', STR_PAD_BOTH)) . '|' . (new Formatter())->setForeground('blue')->apply(str_pad($method, 10, ' ', STR_PAD_BOTH)) . ' ' . (new Formatter())->setForeground('cyan')->apply(str_pad(round($event->duration * 1000, 4) . 'ms', 10, ' ', STR_PAD_RIGHT)) . $uri . PHP_EOL;
                break;
            case $event instanceof OnTask:
                $this->output->debug('[DEBUG]');
                break;
        }
    }
}
