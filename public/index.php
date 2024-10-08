<?php

use App\Controllers\IndexController;
use App\Middlewares\ExceptionHandleMiddleware;
use App\Middlewares\RouteDispatcher;
use App\Middlewares\ParseBodyMiddleware;
use App\Middlewares\SessionMiddleware;
use App\Middlewares\VerifyCSRFToken;
use App\Response;
use App\ServerRequest;
use Next\Http\Server\FPMResponseEmitter;
use Next\Http\Server\RequestHandler;
use Next\Routing\Router;

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');
require_once BASE_PATH . 'vendor/autoload.php';

$router = new Router();

$router->middleware(new SessionMiddleware(), new VerifyCSRFToken())
       ->group(function (Router $router) {
           $router->get('/', [new IndexController(), 'index']);
           $router->get('openapi', [new IndexController(), 'opanapi']);
       });
$router->prefix('api')
       ->middleware(new ParseBodyMiddleware())
       ->group(function (Router $router) {
           $router->get('/', function () {
               return Response::JSON(['version' => '0.1.1']);
           });
       });

$response = (new RequestHandler())
    ->withMiddleware(new ExceptionHandleMiddleware(), new RouteDispatcher($router))
    ->handle(ServerRequest::createFromGlobals());

(new FPMResponseEmitter())->emit($response);