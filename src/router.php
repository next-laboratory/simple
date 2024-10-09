<?php

use App\Controllers\IndexController;
use App\Middlewares\ParseBodyMiddleware;
use App\Middlewares\RouteDispatcher;
use App\Middlewares\SessionMiddleware;
use App\Middlewares\VerifyCSRFToken;
use App\Response;
use Next\Routing\Router;

return (new RouteDispatcher())->withRoutes(function (Router $router) {
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
});