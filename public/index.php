<?php

use App\Bootstrap;
use App\Http\Kernel;
use Max\Di\Context;

define('BASE_PATH', dirname(__DIR__) . '/');

(function() {
    $loader = require_once '../vendor/autoload.php';
    Bootstrap::boot($loader, false);
    Context::getContainer()->make(Kernel::class)->handleFPMRequest();
})();


