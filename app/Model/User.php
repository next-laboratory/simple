<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Model;

use Max\Database\Eloquent\Model;

/**
 * @property int $id
 */
class User extends Model
{
    protected static string $table    = 'users';
    protected array         $hidden   = ['password'];
    protected static array  $fillable = ['id', 'name', 'nickname'];
}
