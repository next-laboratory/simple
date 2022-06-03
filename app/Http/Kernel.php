<?php

namespace App\Http;

use App\Http\Controllers\IndexController;
use Max\HttpServer\Context;
use Max\Routing\Router;

class Kernel extends \Max\HttpServer\Kernel
{
    protected function map(Router $router): void
    {
        $router->group(function(Router $router) {
            $router->get('/', [IndexController::class, 'index']);
            $router->get('/test', function(Context $ctx) {
                return $ctx->HTML('new');
            });
        });
    }
}
