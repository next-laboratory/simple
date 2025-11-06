<?php

use App\Controllers\IndexController;
use App\Middlewares\ParseBodyMiddleware;
use App\Middlewares\RouteDispatcher;
use App\Response;
use Next\Routing\Router;

return (new RouteDispatcher())->withRoutes(function (Router $router) {
    $router->get('/', [new IndexController(), 'index']);
    $router->prefix('api')
        ->middleware(new ParseBodyMiddleware())
        ->group(function (Router $router) {
            $router->get('/', function () {
                return Response::JSON(['foo' => 'bar']);
            });
        });
});
