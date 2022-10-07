<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Listener;

use Max\Database\Event\QueryExecuted;
use Max\Event\Contract\EventListenerInterface;
use Psr\Log\LoggerInterface;

class DatabaseQueryListener implements EventListenerInterface
{
    public function __construct(
        protected LoggerInterface $logger
    ) {
    }

    public function listen(): iterable
    {
        return [
            QueryExecuted::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof QueryExecuted) {
            $this->logger->get('sql')->debug($event->query, ['time' => $event->time, 'bindings' => $event->bindings]);
        }
    }
}
