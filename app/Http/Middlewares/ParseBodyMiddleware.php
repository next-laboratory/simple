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

namespace App\Http\Middlewares;

use Max\Di\Exceptions\NotFoundException;
use Max\Http\Exceptions\HttpException;
use Max\Http\Exceptions\InvalidRequestHandlerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;

/**
 * 自动请求编码方式为json的时候自动将json转为数组
 */
class ParseBodyMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws HttpException
     * @throws NotFoundException
     * @throws InvalidRequestHandlerException
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->isValid($request)) {
            $body = $request->getBody()->getContents();
            $body = json_decode($body, true);
            $request->setPsr7($request->withParsedBody(array_replace_recursive($request->post(), $body)));
        }

        return $handler->handle($request);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     * @throws HttpException
     */
    protected function isValid(ServerRequestInterface $request): bool
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])) {
            return 0 === strcasecmp($request->getHeaderLine('Content-Type'), 'application/json');
        }
        return false;
    }
}
