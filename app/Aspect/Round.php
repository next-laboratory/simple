<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Aspect;

use App\Http\Controller\IndexController;
use Attribute;
use Max\Aop\Attribute\AspectConfig;
use Max\Aop\Contract\AspectInterface;
use Max\Aop\JoinPoint;

#[\Attribute(\Attribute::TARGET_METHOD)]
#[AspectConfig(IndexController::class, 'index', ['round1'])]
class Round implements AspectInterface
{
    public function __construct(
        protected $value
    ) {
    }

    public function process(JoinPoint $joinPoint, \Closure $next)
    {
        dump('Before:' . $this->value . ' ' . $joinPoint->class . '@' . $joinPoint->method);
        $result = $next($joinPoint);
        dump('After:' . $this->value . ' ' . $joinPoint->class . '@' . $joinPoint->method);
        return $result;
    }
}
