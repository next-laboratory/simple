<?php

use App\Http\Kernel;
use Dotenv\Dotenv;
use Max\Config\Repository;
use Max\Di\Context;

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
    $container->make(Kernel::class)->handleFPMRequest();
})();


