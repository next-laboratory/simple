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

use Max\Di\Annotations\Inject;
use Max\Routing\Annotations\Controller;
use Max\Routing\Annotations\GetMapping;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Controller(prefix: '/')]
class IndexController
{
    #[Inject]
    protected ServerRequestInterface $request;
    #[Inject]
    protected ResponseInterface      $response;

    /**
     * @return array
     */
    #[GetMapping(path: '/')]
    public function index(): array
    {
        return $this->response->success(message: 'Hello, ' . $this->request->get('name', 'MaxPHP') . '!');
    }
}
