<?php

use App\Http\Kernel;
use Dotenv\Dotenv;
use Max\Config\Repository;
use Max\Di\Context;
use Max\Event\Contracts\EventListenerInterface;
use Max\Event\ListenerProvider;

require_once '../vendor/autoload.php';

(function() {
    Dotenv::createImmutable(dirname(__DIR__))->load();
    $container = Context::getContainer();
    /** @var Repository $repository */
    $repository = $container->make(Repository::class);
    $repository->scan('../config');
    foreach ($repository->get('di.bindings', []) as $id => $value) {
        $container->bind($id, $value);
    }
    /** @var ListenerProvider $listenerProvider */
    $listenerProvider = $container->make(ListenerProvider::class);
    foreach ($repository->get('listeners', []) as $listener) {
        $listener = $container->make($listener);
        /** @var EventListenerInterface $listener */
        $listenerProvider->addListener($listener);
    }
    $container->make(Kernel::class)->handleFPMRequest();
})();


