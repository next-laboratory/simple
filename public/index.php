<?php

use App\Middlewares\ExceptionHandleMiddleware;
use App\Middlewares\FPMResponseEmitter;
use App\ServerRequest;
use Next\Http\Server\RequestHandler;

require_once '../app/bootstrap.php';

(new RequestHandler())
    ->withMiddleware(
        new FPMResponseEmitter(),
        new ExceptionHandleMiddleware(),
        require_once base_path('app/router.php'),
    )
    ->handle(ServerRequest::createFromGlobals());
