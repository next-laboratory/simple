<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Response;
use Max\Http\Message\Contract\HeaderInterface;
use Max\Utils\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BasicAuthentication implements MiddlewareInterface
{
    protected array $need = ['*'];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Collection::make($this->need)->first(function($pattern) use ($request) {
            return $request->is($pattern);
        })) {
            if ($header = $request->getHeaderLine(HeaderInterface::HEADER_AUTHORIZATION)) {
                [, $authorization] = explode(' ', $header);
                [$user, $password] = explode(':', (string)base64_decode($authorization));
                if ($this->shouldPass($user, $password)) {
                    return $handler->handle($request);
                }
            }
            return $this->shouldAuth();
        }
        return $handler->handle($request);
    }

    protected function shouldAuth(): ResponseInterface
    {
        return new Response(401, [
            HeaderInterface::HEADER_WWW_AUTHENTICATE => 'Basic realm="Input your ID and password"'
        ]);
    }

    public function shouldPass(string $user, string $password): bool
    {
        return $user === 'user' && $password === 'password';
    }
}
