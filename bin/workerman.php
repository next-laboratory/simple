<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

use App\Middlewares\ExceptionHandleMiddleware;
use App\Middlewares\WorkermanResponseEmitter;
use App\ServerRequest;
use Next\Aop\Aop;
use Next\Http\Server\RequestHandler;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');
require_once BASE_PATH . 'vendor/autoload.php';

Aop::init(
    [base_path('src')],
    [
        \Next\Aop\Collector\PropertyAttributeCollector::class,
        \Next\Aop\Collector\AspectCollector::class,
    ],
    base_path('runtime/aop'),
);
$worker            = new Worker('http://0.0.0.0:8989');
$routeDispatcher   = require_once base_path('src/router.php');
$worker->onMessage = function (TcpConnection $connection, Request $request) use ($routeDispatcher) {
    try {
        (new RequestHandler())
            ->withMiddleware(
                new WorkermanResponseEmitter($connection),
                new ExceptionHandleMiddleware(),
                $routeDispatcher
            )
            ->handle(ServerRequest::createFromWorkerManRequest($request));
    } catch (Exception $e) {
        $connection->send('Internal Server Error');
    }
};

Worker::runAll();
