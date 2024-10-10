<?php

namespace App\Middlewares;

use Next\Http\Message\Cookie;
use Next\Http\Message\Stream\FileStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoole\Http\Response;

class SwooleResponseEmitter implements MiddlewareInterface
{
    public function __construct(
        protected Response $response,
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $psrResponse = $handler->handle($request);
        $this->response->status($psrResponse->getStatusCode(), $psrResponse->getReasonPhrase());
        foreach ($psrResponse->getHeader('Set-Cookie') as $cookieLine) {
            $cookie = Cookie::parse($cookieLine);
            $this->response->cookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpires(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->isSecure(),
                $cookie->isHttponly(),
                $cookie->getSameSite()
            );
        }
        $psrResponse = $psrResponse->withoutHeader('Set-Cookie');
        foreach ($psrResponse->getHeaders() as $key => $value) {
            $this->response->header($key, implode(', ', $value));
        }
        $body = $psrResponse->getBody();
        switch (true) {
            case $body instanceof FileStream:
                $this->response->sendfile($body->getFilename(), $body->getOffset(), $body->getLength());
                break;
            default:
                $this->response->end($body?->getContents());
        }
        $body?->close();

        return $psrResponse;
    }
}