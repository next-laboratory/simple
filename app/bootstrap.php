<?php

use App\Logger;
use Dotenv\Dotenv;
use Max\Config\Repository;
use Max\Di\Context;
use Max\Event\ListenerProvider;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

require_once './vendor/autoload.php';

$container = Context::getContainer();

register_shutdown_function(function () use ($container) {
    if ($error = error_get_last()) {
        $container->make(Logger::class)->error($error['message'], [
            'type' => $error['type'],
            'file' => $error['file'],
            'line' => $error['line'],
        ]);
    }
});

// Initialize environment variables and configurations
if (file_exists($envFile = base_path('.env'))) {
    if (method_exists('Dotenv\Dotenv', 'createUnsafeImmutable')) {
        Dotenv::createUnsafeImmutable(base_path())->load();
    } else {
        Dotenv::createMutable(base_path())->load();
    }
}

$repository = $container->make(Repository::class);
$repository->scan(base_path('./config'));
// Initialize bindings
foreach ($repository->get('app.bindings', []) as $id => $value) {
    $container->bind($id, $value);
}

// Initialize event listeners
$listenerProvider = $container->make(ListenerProvider::class);
if (!empty($listeners = $repository->get('app.listeners', []))) {
    foreach ($listeners as $listener) {
        $listenerProvider->addListener($container->make($listener));
    }
}
