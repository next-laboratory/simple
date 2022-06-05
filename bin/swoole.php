<?php

use App\Bootstrap;
use App\Http\Kernel;
use Max\Di\Context;
use Swoole\Constant;
use Swoole\Http\Server;

define('BASE_PATH', dirname(__DIR__) . '/');

(function() {
    $loader = require_once './vendor/autoload.php';
    Bootstrap::boot($loader, true);
    $server = new Server('0.0.0.0', 8989);
    $server->on('request', [Context::getContainer()->make(Kernel::class), 'handleSwooleRequest']);
    $server->set([
        Constant::OPTION_WORKER_NUM => 4,
    ]);
    echo <<<EOT
,--.   ,--.                  ,------. ,--.  ,--.,------.  
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. ' 
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' | 
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'  
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--' 

EOT;

    $server->start();
})();

