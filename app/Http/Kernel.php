<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http;

use App\Http\Controllers\IndexController;
use Max\Http\Server\Kernel as HttpKernel;
use Max\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

class Kernel extends HttpKernel
{
    /**
     * Global middleware.
     */
    protected array $middlewares = [
        'App\Http\Middlewares\ExceptionHandleMiddleware',
        //        'App\Http\Middlewares\AllowCrossDomain',
        'Max\Http\Server\Middlewares\RoutingMiddleware',
        'App\Http\Middlewares\SessionMiddleware',
        'App\Http\Middlewares\ViewMiddleware',
        //        'App\Http\Middlewares\VerifyCSRFToken',
    ];

    /**
     * Register routes.
     */
    protected function map(Router $router): void
    {
        $router->group(function (Router $router) {
            $router->request('/', [IndexController::class, 'index']);
            $router->get('/welcome', 'App\Http\Controllers\IndexController@index');
            $router->get('/test', function (ServerRequestInterface $request) {
                return Response::HTML('test');
            });
        });
    }
}
