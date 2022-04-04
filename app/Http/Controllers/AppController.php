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

namespace App\Http\Controllers;

use Max\Foundation\Http\Annotations\Controller;
use Max\Foundation\Http\Annotations\GetMapping;
use Max\Routing\Route;
use Max\Routing\RouteCollector;
use Psr\Http\Message\ResponseInterface;
use Throwable;

#[Controller(prefix: '/app')]
class AppController
{
    /**
     * @throws Throwable
     */
    #[GetMapping(path: '/routes')]
    public function routes(RouteCollector $routeCollector): ResponseInterface
    {
        $routes = [];
        foreach ($routeCollector->all() as $registeredRoute) {
            foreach ($registeredRoute as $route) {
                foreach ($route as $item) {
                    if (!in_array($item, $routes)) {
                        $routes[] = $item;
                    }
                }
            }
        }
        $routes = collect($routes)->unique()->sortBy(function($item) {
            /** @var Route $item */
            return $item->getPath();
        });
        return view('app.routes', ['routes' => $routes]);
    }
}
