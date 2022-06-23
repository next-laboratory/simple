<?php

declare(strict_types=1);

/**
 * This file is part of the Max package.
 *
 * (c) Cheng Yao <987861463@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http;

use App\Http\Controllers\IndexController;
use Max\Http\Server\Kernel as HttpKernel;
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
        //        'App\Http\Middlewares\AllowCrossDomain',
        'Max\Http\Server\Middlewares\RoutingMiddleware',
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
            $router->get('/welcome', 'App\Http\Controllers\IndexController@index');
            $router->get('/test', function(ServerRequestInterface $request) {
                return Response::HTML('test');
            });
        });
    }
}
