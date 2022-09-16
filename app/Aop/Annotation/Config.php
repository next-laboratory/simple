<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Aop\Annotation;

use Attribute;
use Max\Aop\Contract\PropertyAnnotation;
use Max\Aop\Exception\PropertyHandleException;
use Max\Config\Contract\ConfigInterface;
use Max\Di\Reflection;
use Psr\Container\ContainerExceptionInterface;
use ReflectionException;
use Throwable;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Config implements PropertyAnnotation
{
    /**
     * @param string $key     键
     * @param mixed  $default 默认值
     */
    public function __construct(
        protected string $key,
        protected mixed $default = null
    ) {
    }

    public function handle(object $object, string $property): void
    {
        try {
            $reflectionProperty = Reflection::property($object::class, $property);
            $reflectionProperty->setAccessible(true); // 兼容PHP8.0
            $reflectionProperty->setValue($object, $this->getConfigValue());
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
    protected function getConfigValue()
    {
        return make(ConfigInterface::class)->get($this->key, $this->default);
    }
}
