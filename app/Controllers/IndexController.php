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

namespace App\Controllers;

use Max\Di\Annotations\Inject;
use Max\Http\Annotations\Controller;
use Max\Http\Annotations\GetMapping;
use Psr\Http\Message\ServerRequestInterface;

#[Controller(prefix: '/')]
class IndexController
{
    #[Inject]
    protected ServerRequestInterface $request;

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
            'message' => 'Hello, ' . $this->request->get('name', 'MaxPHP') . '!',
        ];
    }
}
