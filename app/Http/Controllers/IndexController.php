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

use App\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return Response::JSON([
            'code'    => 0,
            'status'  => true,
            'message' => 'Hello, ' . $request->get('name', 'MaxPHP') . '!',
            'data'    => [],
        ]);
    }
}
