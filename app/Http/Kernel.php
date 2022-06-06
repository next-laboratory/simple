<?php

namespace App\Http;

use App\Http\Controllers\IndexController;
use Max\HttpServer\Kernel as HttpKernel;
use Max\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

class Kernel extends HttpKernel
{
    /**
     * Global middleware, executed before routing matches.
     *
     * @var string[]
     */
    protected array $middlewares = [
        'App\Http\Middlewares\ExceptionHandleMiddleware',
        'Max\HttpServer\Middlewares\RoutingMiddleware',
        //        'App\Http\Middlewares\SessionMiddleware',
    ];

    /**
     * Register routes.
     *
     * @param Router $router
     *
     * @return void
     */
    protected function map(Router $router): void
    {
        $router->group(function(Router $router) {
            $router->request('/', [IndexController::class, 'index']);
            $router->get('/welcome', 'App\Http\Controllers\IndexController@welcome');
            $router->get('/test', function(ServerRequestInterface $request) {
                return (new Response())->HTML('test');
            });
        });
    }
}
