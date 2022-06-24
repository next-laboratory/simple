<?php

namespace App\Http\Middlewares;

use App\Exceptions\CSRFException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VerifyCSRFToken implements MiddlewareInterface
{
    /**
     * @throws CSRFException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->isMethod('POST')) {
            $token = $request->getHeaderLine('X-CSRF-Token') ?: $request->post('_token');
            if (is_null($token) || $request->session()->get('_token') !== $token) {
                throw new CSRFException('CSRF token is invalid', 419);
            }
        }
        return $handler->handle($request);
    }
}
