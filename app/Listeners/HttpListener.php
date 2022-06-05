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
//        dump($event);
    }
}
