<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

use App\Http\Kernel;
use App\Http\ServerRequest;
use Next\Http\Server\WorkerManResponseEmitter;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');
require_once BASE_PATH . 'vendor/autoload.php';

$worker            = new Worker('http://0.0.0.0:8989');
$kernel            = new Kernel();
$worker->onMessage = function (TcpConnection $connection, Request $request) use ($kernel) {
    $psrRequest = ServerRequest::createFromWorkerManRequest($request, ['TcpConnection' => $connection, 'request' => $request]);
    (new WorkerManResponseEmitter())->emit($kernel->handle($psrRequest), $connection);
};

Worker::runAll();
