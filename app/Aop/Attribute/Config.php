<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Aop\Attribute;

use Attribute;
use Next\Aop\Contract\PropertyAttribute;
use Next\Aop\Exception\PropertyHandleException;
use Next\Config\Repository;
use Next\Di\Reflection;
use Psr\Container\ContainerExceptionInterface;
use ReflectionException;
use Throwable;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Config implements PropertyAttribute
{
    /**
     * @param string $key     键
     * @param mixed  $default 默认值
     */
    public function __construct(
        protected string $key,
        protected mixed  $default = null
    )
    {
    }

    public function handle(object $object, string $property): void
    {
        try {
            $reflectionProperty = Reflection::property($object::class, $property);
            $reflectionProperty->setAccessible(true); // 兼容PHP8.0
            $reflectionProperty->setValue($object, $this->getValue());
        } catch (Throwable $e) {
            throw new PropertyHandleException('Property assign failed. ' . $e->getMessage());
        }
    }

    /**
     * 获取配置值
     *
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    protected function getValue()
    {
        return make(Repository::class)->get($this->key, $this->default);
    }
}
