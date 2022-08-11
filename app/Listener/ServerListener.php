<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Listener;

use Max\Event\Annotation\Listen;
use Max\Event\Contract\EventListenerInterface;
use Max\Http\Server\Events\OnRequest;

#[Listen]
class ServerListener implements EventListenerInterface
{
    public function listen(): iterable
    {
        return [
            OnRequest::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof OnRequest) {
            $statusCode = $event->response->getStatusCode();
            $statusCode = ($statusCode == 200 ? "\033[42m" : "\033[41m") . $statusCode . "\033[0m";
            echo sprintf("[MaxPHP] %s |%s|%10.3fms| %15s|\033[44m%7s\033[0m| \"%s\"\n", date('Y/m/d H:i:s'), $statusCode, (microtime(true) - $event->requestedAt) * 1000, $event->request->getRealIp(), $event->request->getMethod(), $event->request->getUri()->getPath());
        }
    }
}
