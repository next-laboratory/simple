<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Aop\Collector;

use App\Aop\Annotation\AsCommand;
use Max\Aop\Collector\AbstractCollector;

class CommandCollector extends AbstractCollector
{
    protected static array $container = [];

    public static function collectClass(string $class, object $attribute): void
    {
        if ($attribute instanceof AsCommand) {
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
