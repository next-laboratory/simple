<?php

namespace App\Http\Middlewares;

use App\Http\RequestHandler;
use Next\Routing\Router;
use Next\Routing\UrlMatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteDispatcher implements MiddlewareInterface
{
    protected UrlMatcher $urlMatcher;

    public function __construct(
        protected Router $router,
    )
    {
        $this->urlMatcher = new UrlMatcher($this->router->getRouteCollection());
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandler         $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->urlMatcher->matchRequest($request);
        return $handler->withHandler(new RequestHandler($route))
                       ->withMiddleware(...$route->getMiddlewares())
                       ->handle($request);
    }
}
