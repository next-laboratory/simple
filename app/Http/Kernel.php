<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http;

use App\Http\Controller\IndexController;
use Max\Http\Server\Kernel as HttpKernel;
use Max\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

class Kernel extends HttpKernel
{
    /**
     * Global middleware.
     */
    protected array $middlewares = [
        'App\Http\Middleware\ExceptionHandleMiddleware',
        'Max\Http\Server\Middleware\RoutingMiddleware',
    ];

    /**
     * Web middlewares.
     */
    protected array $webMiddlewares = [
        'App\Http\Middleware\SessionMiddleware',
        'App\Http\Middleware\VerifyCSRFToken',
    ];

    /**
     * Api middlewares.
     */
    protected array $apiMiddlewares = [
        'App\Http\Middleware\AllowCrossDomain',
    ];

    /**
     * Register routes.
     */
    protected function map(Router $router): void
    {
        $router->middleware(...$this->webMiddlewares)
               ->group(function(Router $router) {
                   $router->request('/', [IndexController::class, 'index']);
                   $router->request('/test', [IndexController::class, 'test']);
               });
        $router->middleware(...$this->apiMiddlewares)
               ->prefix('api')
               ->group(function(Router $router) {
                   $router->get('/', function(ServerRequestInterface $request) {
                       return Response::JSON([
                           'statue'  => true,
                           'code'    => 0,
                           'message' => sprintf('Hello, %s.', $request->get('name', 'world')),
                           'data'    => [],
                       ]);
                   });
               });
    }
}
