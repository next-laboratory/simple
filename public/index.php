<?php

use App\Middlewares\ExceptionHandleMiddleware;
use App\ServerRequest;
use Next\Http\Server\FPMResponseEmitter;
use Next\Http\Server\RequestHandler;

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');
require_once BASE_PATH . 'vendor/autoload.php';

$routeDispatcher = require_once base_path('src/router.php');
$response        = (new RequestHandler())
    ->withMiddleware(new ExceptionHandleMiddleware(), $routeDispatcher)
    ->handle(ServerRequest::createFromGlobals());

(new FPMResponseEmitter())->emit($response);