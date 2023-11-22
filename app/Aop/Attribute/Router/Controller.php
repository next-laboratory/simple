<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Aop\Attribute\Router;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Controller
{
    /**
     * @param string $prefix      前缀
     * @param array  $middlewares 中间件
     * @param array  $patterns    参数规则
     */
    public function __construct(
        public string $prefix = '/',
        public array  $middlewares = [],
        public array  $patterns = [],
    )
    {
    }
}
