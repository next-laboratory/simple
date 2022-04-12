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

namespace App\Http\Controllers;

use App\Http\AbstractController;
use Max\Http\Annotations\Controller;
use Max\Http\Annotations\GetMapping;

#[Controller(prefix: '/')]
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    #[GetMapping(path: '/')]
    public function index(): array
    {
        return [
            'status'  => true,
            'code'    => 0,
            'data'    => [],
            'message' => 'Welcome.',
        ];
    }
}
