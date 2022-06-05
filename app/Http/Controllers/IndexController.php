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

use Max\HttpServer\Context;
use Max\Routing\Annotations\Controller;
use Max\Routing\Annotations\GetMapping;
use Max\View\Renderer;
use Psr\Http\Message\ResponseInterface;

/**
 * 当前类使用了注解定义路由，只在swoole/workerman环境下有效
 * cli-server或FPM环境下不支持注解/AOP，需要在App\Http\Kernel中定义路由
 */
#[Controller(prefix: '/')]
class IndexController
{
    public function index(Context $ctx): ResponseInterface
    {
        return $ctx->JSON([
            'code'    => 0,
            'status'  => true,
            'message' => 'Hello, ' . $ctx->get('name', 'MaxPHP') . '!',
            'data'    => [],
        ]);
    }

    #[GetMapping(path: '/welcome')]
    public function welcome(Context $ctx, Renderer $renderer): ResponseInterface
    {
        return $ctx->end($renderer->render('index', ['a' => 123]));
    }
}
