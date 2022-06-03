<?php

namespace App\Listeners;

use Max\Event\Annotations\Listen;
use Max\Event\Contracts\EventListenerInterface;
use Max\HttpServer\Events\OnRequest;

#[Listen]
class HttpListener implements EventListenerInterface
{
    public function listen(): iterable
    {
        return [
            OnRequest::class,
        ];
    }

    public function process(object $event): void
    {
        dump($event);
    }
}
