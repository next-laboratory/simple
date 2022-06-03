<?php

require_once '../vendor/autoload.php';

\Max\Di\Context::getContainer()->bind(\Max\HttpServer\Contracts\ExceptionHandlerInterface::class, \Max\HttpServer\ExceptionHandler::class);

$requestHandler = \Max\Di\Context::getContainer()->make(\App\Kernel::class);

$requestHandler->handleFPMRequest();
