<?php

namespace App\Middlewares;

use InvalidArgumentException;
use Next\Http\Message\Cookie;
use Next\Http\Message\Stream\FileStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Response;

class WorkermanResponseEmitter implements MiddlewareInterface
{
    public function __construct(
        protected TcpConnection $connection,
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $psrResponse = $handler->handle($request);
        $response    = new Response($psrResponse->getStatusCode());
        $cookies     = $psrResponse->getHeader('Set-Cookie');
        $psrResponse = $psrResponse->withoutHeader('Set-Cookie');
        foreach ($psrResponse->getHeaders() as $name => $values) {
            $response->header($name, implode(', ', $values));
        }
        $body = $psrResponse->getBody();
        if ($body instanceof FileStream) {
            $this->connection->send($response->withFile($body->getFilename(), $body->getOffset(), $body->getLength()));
        } else {
            foreach ($cookies as $cookie) {
                $cookie = Cookie::parse($cookie);
                $response->cookie(
                    $cookie->getName(),
                    $cookie->getValue(),
                    $cookie->getMaxAge(),
                    $cookie->getPath(),
                    $cookie->getDomain(),
                    $cookie->isSecure(),
                    $cookie->isHttponly(),
                    $cookie->getSameSite()
                );
            }
            $this->connection->send($response->withBody((string)$body?->getContents()));
        }
        $body?->close();
        $this->connection->close();

        return $psrResponse;
    }
}