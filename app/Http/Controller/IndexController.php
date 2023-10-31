<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Http\Controller;

use App\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController
{
    /**
     * 注意： 如果需要使用请求变量，切记变量名为$request，否则不能注入.
     */
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return Response::text(sprintf('Hello, %s.', $request->query('name', 'world')));
    }
}
