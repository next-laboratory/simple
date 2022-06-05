<?php

use App\Bootstrap;
use App\Http\Kernel;
use Max\Di\Context;
use Workerman\Worker;

define('BASE_PATH', dirname(__DIR__) . '/');

(function() {
    $loader = require_once './vendor/autoload.php';
    Bootstrap::boot($loader, true);
    $worker            = new Worker('http://0.0.0.0:8989');
    $worker->onMessage = [Context::getContainer()->make(Kernel::class), 'handleWorkermanRequest'];
    $worker->count     = 4;

    echo <<<EOT
,--.   ,--.                  ,------. ,--.  ,--.,------.  
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. ' 
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' | 
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'  
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--' 

EOT;

    Worker::runAll();
})();

