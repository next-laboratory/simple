<?php

namespace App;

use App\Middlewares\ExceptionHandleMiddleware;
use App\Middlewares\RouteDispatcher;
use Dotenv\Dotenv;

class App extends \Next\Http\Server\RequestHandler
{
    protected RouteDispatcher $routeDispatcher;

    public function __construct()
    {
        // Initialize environment variables and configurations
        if (file_exists($envFile = base_path('.env'))) {
            if (method_exists('Dotenv\Dotenv', 'createUnsafeImmutable')) {
                Dotenv::createUnsafeImmutable(base_path())->load();
            } else {
                Dotenv::createMutable(base_path())->load();
            }
        }

        $this->routeDispatcher = require_once base_path('src/router.php');
    }

    public function withGlobalMiddlewares(): array
    {
        return [
            new ExceptionHandleMiddleware(),
            $this->routeDispatcher,
        ];
    }
}