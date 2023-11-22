<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Aop\Collector;

use App\Aop\Attribute\Command;
use Next\Aop\Collector\AbstractCollector;

class CommandCollector extends AbstractCollector
{
    protected static array $container = [];

    public static function collectClass(string $class, object $attribute): void
    {
        if ($attribute instanceof Command) {
            self::add($class);
        }
    }

    public static function add(string $class): void
    {
        if (!in_array($class, self::$container)) {
            self::$container[] = $class;
        }
    }

    public static function all(): array
    {
        return self::$container;
    }
}
