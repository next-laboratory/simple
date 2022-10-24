<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http;

use Max\Http\Server\Kernel as HttpKernel;
use Max\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

class Kernel extends HttpKernel
{
    /**
     * Global middleware.
     */
    protected array $middlewares = [
        \App\Http\Middleware\ExceptionHandleMiddleware::class,
        \App\Http\Middleware\AllowCrossDomain::class,
        \Max\Http\Server\Middleware\RoutingMiddleware::class,
    ];

    /**
     * Web middlewares.
     */
    protected array $webMiddlewares = [
        \App\Http\Middleware\VerifyCSRFToken::class,
        //        \Max\Http\Server\Middleware\SessionMiddleware::class,
    ];

    /**
     * Api middlewares.
     */
    protected array $apiMiddlewares = [
        \App\Http\Middleware\ParseBodyMiddleware::class,
    ];

    /**
     * Register routes.
     */
    protected function map(Router $router): void
    {
        $router->middleware(...$this->webMiddlewares)
               ->group(function (Router $router) {
                   $router->request('/', [\App\Http\Controller\IndexController::class, 'index']);
               });
        $router->middleware(...$this->apiMiddlewares)
               ->prefix('api')
               ->group(function (Router $router) {
                   $router->get('/', function (ServerRequestInterface $request) {
                       return Response::JSON([
                           'status'  => true,
                           'code'    => 0,
                           'message' => sprintf('Hello, %s.', $request->query('name', 'world')),
                           'data'    => [],
                       ]);
                   });
               });
    }
}
