<?php

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
        'nickname'
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
