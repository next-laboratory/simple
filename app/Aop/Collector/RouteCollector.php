<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Aop\Collector;

use Max\Aop\Collector\AbstractCollector;
use Max\Di\Context;
use Max\Di\Exception\NotFoundException;
use Max\Routing\Annotation\Controller;
use Max\Routing\Annotation\RequestMapping;
use Max\Routing\Router;
use Psr\Container\ContainerExceptionInterface;
use ReflectionException;

class RouteCollector extends AbstractCollector
{
    /**
     * 当前控制器对应的router.
     */
    protected static ?Router $router = null;

    /**
     * 当前控制器的类名.
     */
    protected static string $class = '';

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public static function collectClass(string $class, object $attribute): void
    {
        if ($attribute instanceof Controller) {
            $routeCollector = Context::getContainer()->make(\Max\Routing\RouteCollector::class);
            $router         = new Router($attribute->prefix, $attribute->patterns, middlewares: $attribute->middlewares, routeCollector: $routeCollector);
            self::$router   = $router;
            self::$class    = $class;
        }
    }

    /**
     * @throws NotFoundException
     */
    public static function collectMethod(string $class, string $method, object $attribute): void
    {
        if ($attribute instanceof RequestMapping && self::$class === $class && ! is_null(self::$router)) {
            self::$router->request($attribute->path, [$class, $method], $attribute->methods)->middleware(...$attribute->middlewares);
        }
    }
}
