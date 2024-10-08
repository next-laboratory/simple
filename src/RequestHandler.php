<?php

namespace App;

use Next\Routing\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    public function __construct(
        protected Route $route,
    )
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($attributes = $this->route->getParameters()) {
            foreach ($attributes as $key => $value) {
                $request = $request->withAttribute($key, $value);
            }
        }

        return call_user_func_array($this->route->getAction(), [$request]);
    }
}
