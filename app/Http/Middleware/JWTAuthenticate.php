<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use App\Model\User;
use Exception;
use Max\JWT\JWTAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JWTAuthenticate implements MiddlewareInterface
{
    public function __construct(
        protected JWTAuth $JWTAuth
    ) {
    }

    /**
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($token = $this->JWTAuth->parseToken($request)) {
            $payload = $this->JWTAuth->getPayload(trim($token));
            if ($user = User::findOrFail($payload->aud)) {
                return $handler->handle($request->withAttribute(User::class, $user));
            }
        }
        throw new Exception('Token is invalid');
    }
}
