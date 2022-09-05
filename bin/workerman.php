<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use App\Bootstrap;
use App\Http\Kernel;
use App\Http\ServerRequest;
use Max\Di\Context;
use Max\Http\Server\ResponseEmitter\WorkerManResponseEmitter;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

require_once __DIR__ . '/base.php';

(function () {
    if (! class_exists('Workerman\Worker')) {
        throw new Exception('You should install the workerman using `composer require workerman/workerman` before starting.');
    }
    Bootstrap::boot(true);

    /**
     * Configuration.
     */
    $protocol  = 'http://0.0.0.0:8989';
    $workerNum = 4;

    /**
     * Start server.
     */
    $worker            = new Worker($protocol);
    $kernel            = Context::getContainer()->make(Kernel::class);
    $worker->onMessage = function (TcpConnection $connection, Request $request) use ($kernel) {
        $psrResponse = $kernel->through(ServerRequest::createFromWorkerManRequest($request, [
            'TcpConnection' => $connection,
            'request'       => $request,
        ]));
        (new WorkerManResponseEmitter())->emit($psrResponse, $connection);
    };
    $worker->count     = $workerNum;

    echo <<<'EOT'
,--.   ,--.                  ,------. ,--.  ,--.,------.  
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. ' 
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' | 
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'  
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--' 

EOT;

    Worker::runAll();
})();
