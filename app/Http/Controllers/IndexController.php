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

use App\Http\Middlewares\SessionMiddleware;
use Max\HttpServer\Context;
use Max\Routing\Annotations\Controller;
use Max\Routing\Annotations\GetMapping;
use Max\View\Renderer;
use Psr\Http\Message\ResponseInterface;

#[Controller(prefix: '/')]
class IndexController
{
    #[GetMapping(path: '/', middlewares: [SessionMiddleware::class])]
    public function index(Context $ctx): ResponseInterface
    {
        return $ctx->end('Hello, ' . $ctx->get('name', 'MaxPHP!'));
    }

    #[GetMapping(path: '/welcome')]
    public function welcome(Context $ctx, Renderer $renderer): ResponseInterface
    {
        return $ctx->end($renderer->render('index', ['a' => 123]));
    }
}
