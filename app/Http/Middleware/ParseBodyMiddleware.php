<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use Max\Http\Message\Contract\HeaderInterface;
use Max\Http\Message\Contract\RequestMethodInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 自动请求编码方式为json的时候自动将json转为数组.
 */
class ParseBodyMiddleware implements MiddlewareInterface
{
    /**
     * 下面方法的请求体需要被解析.
     */
    protected array $shouldParseMethods = [
        RequestMethodInterface::METHOD_POST,
        RequestMethodInterface::METHOD_PUT,
        RequestMethodInterface::METHOD_PATCH,
    ];

    /**
     * 解析后替换parsedBody.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->shouldParseBody($request)) {
            $contentType = $request->getHeaderLine(HeaderInterface::HEADER_CONTENT_TYPE);
            if ($content = $request->getBody()?->getContents()) {
                if (str_contains($contentType, 'application/json')) {
                    $request = $request->withParsedBody(json_decode($content, true) ?? []);
                } elseif (str_contains($contentType, 'application/xml')) {
                    $xmlElements = simplexml_load_string($content);
                    $request     = $request->withParsedBody(json_decode(json_encode($xmlElements), true));
                }
            }
        }

        return $handler->handle($request);
    }

    /**
     * 是否需要解析.
     */
    protected function shouldParseBody(ServerRequestInterface $request): bool
    {
        return in_array($request->getMethod(), $this->shouldParseMethods);
    }
}
