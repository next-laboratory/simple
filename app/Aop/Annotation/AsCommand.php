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

#[Attribute(Attribute::TARGET_CLASS)]
class AsCommand
{
}
