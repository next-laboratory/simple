<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Listener;

use Next\Database\Event\QueryExecuted;
use Next\Event\EventListener;
use Psr\Log\LoggerInterface;

class DatabaseQueryListener extends EventListener
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
