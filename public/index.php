<?php

use App\Middlewares\ExceptionHandleMiddleware;
use App\Middlewares\FPMResponseEmitter;
use App\ServerRequest;
use Next\Http\Server\RequestHandler;

define('BASE_PATH', dirname(__DIR__) . '/');
require_once BASE_PATH . 'vendor/autoload.php';

(new RequestHandler())
    ->withMiddleware(
        new FPMResponseEmitter(),
        new ExceptionHandleMiddleware(),
        require_once base_path('app/router.php'),
    )
    ->handle(ServerRequest::createFromGlobals());
