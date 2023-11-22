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

#[Attribute(Attribute::TARGET_METHOD)]
class DeleteMapping extends RequestMapping
{
    /**
     * @var array<int, string>
     */
    public array $methods = ['DELETE'];
}
