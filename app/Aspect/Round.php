<?php

namespace App\Aspect;

use App\Http\Controller\IndexController;
use Attribute;
use Closure;
use Max\Aop\Attribute\AspectConfig;
use Max\Aop\Contract\AspectInterface;
use Max\Aop\JoinPoint;

#[Attribute(Attribute::TARGET_METHOD)]
#[AspectConfig(IndexController::class, 'index', ['round1'])]
class Round implements AspectInterface
{
    public function __construct(
        protected $value
    )
    {
    }

    public function process(JoinPoint $joinPoint, Closure $next)
    {
        dump('Before:' . $this->value . ' ' . $joinPoint->class . '@' . $joinPoint->method);
        $result = $next($joinPoint);
        dump('After:' . $this->value . ' ' . $joinPoint->class . '@' . $joinPoint->method);
        return $result;
    }
}
