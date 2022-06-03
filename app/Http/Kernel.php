<?php

namespace App\Http;

use App\Http\Controllers\IndexController;
use Max\HttpServer\Context;
use Max\HttpServer\Kernel as HttpKernel;
use Max\Routing\Router;

class Kernel extends HttpKernel
{
    protected array $middlewares = [];

    protected function map(Router $router): void
    {
        $router->group(function(Router $router) {
            $router->get('/', [IndexController::class, 'index']);
            $router->get('/test', function(Context $ctx) {
                return $ctx->HTML('test');
            });
        });
    }
}
