<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Http;

use Next\Http\Server\Kernel as HttpKernel;
use Next\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

class Kernel extends HttpKernel
{
    /**
     * Global middleware.
     */
    protected array $middlewares = [
        \App\Http\Middleware\ExceptionHandleMiddleware::class,
        \App\Http\Middleware\CORSMiddleware::class,
        \Next\Http\Server\Middleware\RoutingMiddleware::class,
    ];

    /**
     * Web middlewares.
     */
    protected array $webMiddlewares = [
        \App\Http\Middleware\SessionMiddleware::class,
        \App\Http\Middleware\VerifyCSRFToken::class,
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
                $router->request('/', function (ServerRequestInterface $request) {
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
