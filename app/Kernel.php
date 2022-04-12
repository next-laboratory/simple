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

namespace App;

use Max\Http\RequestHandler;
use Max\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Kernel extends RequestHandler
{
    /**
     * 全局中间件
     *
     * @var array|string[]
     */
    protected array $middlewares = [
        'App\Middlewares\ExceptionHandlerMiddleware',
        'Max\Http\Middlewares\RoutingMiddleware',
//        'App\Middlewares\AllowCrossDomain',
//        'App\Middlewares\ParseBodyMiddleware',
    ];

    /**
     * 注册路由
     *
     * @param Router $router
     */
    protected function map(Router $router)
    {
        $router->group(function (Router $router) {
            $router->request('welcome', function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response->json($request->all());
            });
        });
    }
}
