<?php

namespace App\Http\Middlewares;

use App\Models\User;
use Exception;
use Max\JWT\JWTAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JWTAuthenticate implements MiddlewareInterface
{
    public function __construct(protected JWTAuth $JWTAuth)
    {
    }

    /**
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($token = $this->JWTAuth->parseToken($request)) {
            $payload    = $this->JWTAuth->getPayload(trim($token));
            $identified = $payload->aud;
            if ($user = User::findOrFail($identified)) {
                return $handler->handle($request->withAttribute(User::class, $user));
            }
        }
        throw new Exception('Token is invalid');
    }
}
