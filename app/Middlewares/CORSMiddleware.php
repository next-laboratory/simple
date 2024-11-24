<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Middlewares;

use Next\Http\Message\Contract\StatusCodeInterface;
use Next\Http\Message\Response;
use Next\Utils\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function Next\Utils\collect;

class CORSMiddleware implements MiddlewareInterface
{
    /**
     * @var array 允许域，全部可以使用`*`
     */
    protected array $allowOrigin = ['*'];

    /**
     * @var string[] 允许的头部
     */
    protected array $allowHeaders = [
        'Authorization',
        'Content-Type',
        'If-Match',
        'If-Modified-Since',
        'If-None-Match',
        'If-Unmodified-Since',
        'X-Csrf-Token',
        'X-Requested-With',
    ];

    /**
     * @var array|string[] 允许的方法
     */
    protected array $allowMethods = ['GET', 'POST', 'PATCH', 'PUT', 'DELETE', 'OPTIONS'];

    /**
     * @var string 允许获取凭证
     */
    protected string $allowCredentials = 'true';

    /**
     * @var int Cookie存活时间
     */
    protected int $maxAge = 1800;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->shouldCrossOrigin($origin = $request->getHeaderLine('Origin'))) {
            $headers = $this->createCORSHeaders($origin);
            if (strcasecmp($request->getMethod(), 'OPTIONS') === 0) {
                return new Response(StatusCodeInterface::STATUS_NO_CONTENT, $headers);
            }

            return $this->addHeadersToResponse($handler->handle($request), $headers);
        }

        return $handler->handle($request);
    }

    /**
     * 创建响应头部.
     */
    protected function createCORSHeaders(string $origin): array
    {
        return [
            'Access-Control-Allow-Credentials' => $this->allowCredentials,
            'Access-Control-Max-Age'           => $this->maxAge,
            'Access-Control-Allow-Methods'     => implode(', ', $this->allowMethods),
            'Access-Control-Allow-Headers'     => implode(', ', $this->allowHeaders),
            'Access-Control-Allow-Origin'      => $origin,
        ];
    }

    /**
     * 将头部添加到响应.
     */
    protected function addHeadersToResponse(ResponseInterface $response, array $headers): ResponseInterface
    {
        foreach ($headers as $name => $header) {
            $response = $response->withHeader($name, $header);
        }
        return $response;
    }

    /**
     * 允许跨域
     */
    protected function shouldCrossOrigin(string $origin)
    {
        if (empty($origin)) {
            return false;
        }

        return collect($this->allowOrigin)->first(fn($allowOrigin) => Str::is($allowOrigin, $origin));
    }
}
