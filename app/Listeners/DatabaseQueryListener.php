<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Listeners;

use Max\Database\Events\QueryExecuted;
use Max\Event\Contracts\EventListenerInterface;
use Psr\Log\LoggerInterface;

class DatabaseQueryListener implements EventListenerInterface
{
    public function __construct(protected LoggerInterface $logger)
    {
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
            $this->logger->get('sql')->debug($event->query, [
                'duration' => $event->duration,
                'bindings' => $event->bindings,
            ]);
        }
    }
}
