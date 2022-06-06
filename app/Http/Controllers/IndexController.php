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

use App\Http\Response;
use Max\Routing\Annotations\Controller;
use Max\Routing\Annotations\GetMapping;
use Max\View\Renderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * 当前类使用了注解定义路由，只在swoole/workerman环境下有效
 * cli-server或FPM环境下不支持注解/AOP，需要在App\Http\Kernel中定义路由
 */
#[Controller(prefix: '/')]
class IndexController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return (new Response())->JSON([
            'code'    => 0,
            'status'  => true,
            'message' => 'Hello, ' . $request->get('name', 'MaxPHP') . '!',
            'data'    => [],
        ]);
    }

    #[GetMapping(path: '/welcome')]
    public function welcome(ServerRequestInterface $request, Renderer $renderer): ResponseInterface
    {
        return (new Response())->HTML($renderer->render('index', ['a' => 123]));
    }
}
