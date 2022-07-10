<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middlewares;

use App\Http\Response;
use Max\Http\Message\Stream\FileStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function date_default_timezone_get;

class DownloadCacheMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($modifiedSince = $request->getHeaderLine('if-modified-since')) {
            $body = $response->getBody();
            if ($body instanceof FileStream) {
                $path         = $body->getMetadata('uri');
                $lastModified = date('D, d M Y H:i:s', filemtime($path)) . ' ' . date_default_timezone_get();
                if ($lastModified === $modifiedSince) {
                    return new Response(304);
                }
            }
        }
        return $response;
    }
}
