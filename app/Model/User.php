<?php

declare(strict_types=1);
/**
 * This file is part of the Max package.
 *
 * (c) Cheng Yao <987861463@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Model;

use Max\Database\Eloquent\Model;

/**
 * @property string $username
 */
class User extends Model
{
    /**
     * @var string
     */
    protected string $table = 'user';

    /**
     * @var array|string[]
     */
    protected array $cast = [
        'age'      => 'integer',
        'username' => 'string',
    ];

    protected array $hidden = [
        'password',
    ];
}
