<?php

use App\Kernel;
use Max\Di\Context;
use Max\HttpServer\Contracts\ExceptionHandlerInterface;
use Max\HttpServer\ExceptionHandler;

require_once '../vendor/autoload.php';

(function() {
    Context::getContainer()->bind(ExceptionHandlerInterface::class, ExceptionHandler::class);
    $requestHandler = Context::getContainer()->make(Kernel::class);
    $requestHandler->handleFPMRequest();
})();


