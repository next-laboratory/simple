<?php

namespace App;

use App\Controllers\IndexController;
use App\Middlewares\TestMiddleware;
use Max\HttpServer\Context;
use Max\Routing\Router;

class Kernel extends \Max\HttpServer\Kernel
{
    protected function map(Router $router): void
    {
//        $router->get('/', [IndexController::class, 'index']);
//        $router->get('/test', function(Context $ctx) {
//            return $ctx->HTML('new');
//        });
    }
}
