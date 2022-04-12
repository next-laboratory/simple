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

namespace App\Middlewares;

use Exception;
use Max\Http\Message\Stream\StringStream;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 跨域中间件，如果有预检请求，则需要给路由添加OPTIONS请求方式
 */
class AllowCrossDomain implements MiddlewareInterface
{
    /**
     * 全局跨域
     *
     * @var bool
     */
    protected bool $global = false;

    /**
     * 允许域
     *
     * @var array
     */
    protected array $allowOrigin = [];

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($this->global) {
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        } else {
            if (in_array('*', $this->allowOrigin)) {
                $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            } else if (in_array($allow = $request->getHeaderLine('Origin'), $this->allowOrigin)) {
                $response = $response->withHeader('Access-Control-Allow-Origin', $allow);
            }
        }
        if ($request->isMethod('OPTIONS')) {
            $response = $response->withBody(new StringStream(''))->withStatus('204');
        }
        return $response;
    }
}
