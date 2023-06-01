<?php

namespace App\Aspect;

use App\Http\Controller\IndexController;
use Closure;
use Max\Aop\Attribute\AspectConfig;
use Max\Aop\Contract\AspectInterface;
use Max\Aop\JoinPoint;
use Max\Http\Server\RequestHandler;

#[\Attribute(\Attribute::TARGET_METHOD)]
#[AspectConfig(IndexController::class, 'index', ['hello'])]
#[AspectConfig(RequestHandler::class, 'handle', ['world'])]
class Round implements AspectInterface
{
    public function __construct(
        protected $parameters
    )
    {
    }

    public function process(JoinPoint $joinPoint, Closure $next)
    {
        dump($this->parameters);
        return $next($joinPoint);
    }
}