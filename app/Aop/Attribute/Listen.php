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

#[Attribute(Attribute::TARGET_CLASS)]
class Listen
{
}
