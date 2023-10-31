<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use App\Http\Response;
use Next\Http\Message\Stream\FileStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DownloadCacheMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($modifiedSince = $request->getHeaderLine('if-modified-since')) {
            $body = $response->getBody();
            if ($body instanceof FileStream) {
                $lastModified = date('D, d M Y H:i:s', filemtime($body->getFilename())) . ' ' . \date_default_timezone_get();
                if ($lastModified === $modifiedSince) {
                    return new Response(304);
                }
            }
        }
        return $response;
    }
}
