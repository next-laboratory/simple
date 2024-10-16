<?php

namespace App\Middlewares;

use App\RequestHandler;
use Closure;
use Next\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteDispatcher implements MiddlewareInterface
{
    protected Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function withRoutes(Closure $callback): static
    {
        $callback($this->router);

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandler         $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->matchRequest($request);
        return $handler->withHandler(new RequestHandler($route))
                       ->withMiddleware(...$route->getMiddlewares())
                       ->handle($request);
    }
}
