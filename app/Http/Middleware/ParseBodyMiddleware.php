<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 自动请求编码方式为json的时候自动将json转为数组.
 */
class ParseBodyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (
            in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])
            && str_starts_with($request->getHeaderLine('Content-Type'), 'application/json')
        ) {
            if ($body = $request->getBody()?->getContents()) {
                $request = $request->withParsedBody(array_replace_recursive($request->getParsedBody(), json_decode($body, true) ?? []));
            }
        }

        return $handler->handle($request);
    }
}
