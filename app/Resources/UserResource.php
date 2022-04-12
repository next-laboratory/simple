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

namespace App\Resources;

use Max\Utils\Resources\JsonResource;

/**
 * @property int $id 用户ID
 */
class UserResource extends JsonResource
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
