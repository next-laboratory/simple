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

class Kernel extends HttpKernel
{
    /**
     * Global middleware.
     */
    protected array $middlewares = [
        'App\Http\Middlewares\ExceptionHandleMiddleware',
        'Max\Http\Server\Middlewares\RoutingMiddleware',
    ];

    /**
     * Web middlewares.
     */
    protected array $webMiddlewares = [
        'App\Http\Middlewares\SessionMiddleware',
        'App\Http\Middlewares\ViewMiddleware',
        'App\Http\Middlewares\VerifyCSRFToken',
    ];

    /**
     * Api middlewares.
     */
    protected array $apiMiddlewares = [
        'App\Http\Middlewares\AllowCrossDomain',
    ];

    /**
     * Register routes.
     */
    protected function map(Router $router): void
    {
        $router->middleware(...$this->webMiddlewares)->group(function (Router $router) {
            $router->request('/', [IndexController::class, 'index']);
        });
        $router->middleware(...$this->apiMiddlewares)->prefix('api')->group(function (Router $router) {
            $router->get('/', 'App\Http\Controllers\IndexController@api');
        });
    }
}
