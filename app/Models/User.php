<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Models;

use Max\Database\Eloquent\Model;
use Max\JWT\Contracts\Authenticatable;

/**
 * @property int $id
 */
class User extends Model implements Authenticatable
{
    protected string $table    = 'users';

    protected array  $hidden   = [
        'password',
    ];

    protected array  $fillable = [
        'id',
        'name',
        'nickname',
    ];

    public function getClaims(): array
    {
        return $this->getAttributes();
    }

    public function getIdentifier(): int
    {
        return $this->id;
    }
}
