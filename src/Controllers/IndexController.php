<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Controllers;

use App\Response;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController extends Controller
{
    /**
     * 注意： 如果需要使用请求变量，切记变量名为$request，否则不能注入.
     */
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return Response::text(sprintf('Hello, %s.', $request->query('name', 'world')));
    }

    #[OA\Get(
        path: '/openapi',
        description: '获取接口文档',
        summary: '获取接口文档',
        tags: ['Index'],
        responses: [
            new OA\Response(response: 200, description: '接口文档')
        ]
    )]
    public function opanapi(): ResponseInterface
    {
        $openapi = \OpenApi\Generator::scan([base_path('app/Http/Controllers')]);

        return Response::JSON($openapi->toJson());
    }
}
