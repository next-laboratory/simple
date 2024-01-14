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
use Next\Di\Reflection;
use Psr\Container\ContainerExceptionInterface;
use ReflectionException;
use Throwable;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Inject implements PropertyAttribute
{
    /**
     * @param string $id 注入的类型
     */
    public function __construct(
        protected string $id = ''
    )
    {
    }

    public function handle(object $object, string $property): void
    {
        try {
            $reflectionProperty = Reflection::property($object::class, $property);
            if ((!is_null($type = $reflectionProperty->getType()) && $type = $type->getName()) || $type = $this->id) {
                $reflectionProperty->setValue($object, $this->getBinding($type));
            }
        } catch (Throwable $e) {
            throw new PropertyHandleException('Property assign failed. ' . $e->getMessage());
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    protected function getBinding(string $type): object
    {
        return make($type);
    }
}
