<?php

namespace App\Http;

use App\Http\Controllers\IndexController;
use App\Http\Middlewares\CORSMiddleware;
use App\Http\Middlewares\ExceptionHandleMiddleware;
use App\Http\Middlewares\ParseBodyMiddleware;
use App\Http\Middlewares\RouteDispatcher;
use App\Http\Middlewares\SessionMiddleware;
use App\Http\Middlewares\VerifyCSRFToken;
use Next\Http\Server\RequestHandler;
use Next\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Kernel
{
    protected Router $router;

    public function __construct()
    {
        $this->map($this->router = new Router());
    }

    protected function map(Router $router): void
    {
        $router->middleware(new SessionMiddleware(), new VerifyCSRFToken())
               ->group(function (Router $router) {
                   $router->get('/', [new IndexController(), 'index']);
                   $router->get('openapi', [new IndexController(), 'opanapi'])->middleware(new CORSMiddleware());
               });
        $router->prefix('api')
               ->middleware(new ParseBodyMiddleware())
               ->group(function (Router $router) {
                   $router->get('/', function () {
                       return Response::JSON(['version' => '0.1.1']);
                   });
               });
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return (new RequestHandler())
            ->withMiddleware(new ExceptionHandleMiddleware(), new RouteDispatcher($this->router))
            ->handle($request);
    }
}
