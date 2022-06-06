<?php

use App\Bootstrap;
use App\Http\Kernel;
use Max\Di\Context;
use Workerman\Worker;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
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

