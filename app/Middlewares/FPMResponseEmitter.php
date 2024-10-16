<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FPMResponseEmitter implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if (!headers_sent()) {
            static::sendHeaders($response);
        }
        static::sendContent($response);

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } elseif ('cli' !== PHP_SAPI) {
            static::closeOutputBuffers(0, true);
        }

        return $response;
    }

    protected static function closeOutputBuffers($targetLevel, $flush)
    {
        $status = ob_get_status(true);
        $level  = count($status);
        $flags  = defined('PHP_OUTPUT_HANDLER_REMOVABLE') ? PHP_OUTPUT_HANDLER_REMOVABLE | ($flush ? PHP_OUTPUT_HANDLER_FLUSHABLE : PHP_OUTPUT_HANDLER_CLEANABLE) : -1;

        while ($level-- > $targetLevel && ($s = $status[$level]) && (!isset($s['del']) ? !isset($s['flags']) || $flags === ($s['flags'] & $flags) : $s['del'])) {
            if ($flush) {
                ob_end_flush();
            } else {
                ob_end_clean();
            }
        }
    }

    protected static function sendHeaders(ResponseInterface $response)
    {
        header(sprintf('HTTP/%s %d %s', $response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()), true);
        foreach ($response->getHeader('Set-Cookie') as $cookie) {
            header(sprintf('%s: %s', 'Set-Cookie', $cookie), false);
        }
        $response = $response->withoutHeader('Set-Cookie');
        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . implode(', ', $value));
        }
    }

    protected static function sendContent(ResponseInterface $response)
    {
        $body = $response->getBody();
        echo $body;
        $body?->close();
    }
}