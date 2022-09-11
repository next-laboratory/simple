<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Aop\Aspect;

use Attribute;
use Closure;
use Max\Aop\Contract\AspectInterface;
use Max\Aop\JoinPoint;
use Max\Cache\CacheManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;
use ReflectionException;

#[Attribute(Attribute::TARGET_METHOD)]
class Cacheable implements AspectInterface
{
    public function __construct(
        protected ?int $ttl = null,
        protected string $prefix = '',
        protected ?string $key = null
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException|ReflectionException
     */
    public function process(JoinPoint $joinPoint, Closure $next): mixed
    {
        return make(CacheManager::class)->store()->remember($this->getKey($joinPoint), fn () => $next($joinPoint), $this->ttl);
    }

    protected function getKey(JoinPoint $joinPoint): string
    {
        $key = $this->key ?? ($joinPoint->class . ':' . $joinPoint->method . ':' . serialize(array_filter($joinPoint->parameters->getArrayCopy(), fn ($item) => ! is_object($item))));
        return $this->prefix ? ($this->prefix . ':' . $key) : $key;
    }
}
