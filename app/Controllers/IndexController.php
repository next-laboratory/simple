<?php

namespace App\Controllers;

use Max\HttpServer\Context;
use Max\Routing\Annotations\Controller;
use Max\Routing\Annotations\GetMapping;
use Psr\Http\Message\ResponseInterface;

#[Controller(prefix: '/')]
class IndexController
{
    #[GetMapping(path: '/')]
    public function index(Context $ctx): ResponseInterface
    {
        return $ctx->HTML('Hello, ' . $ctx->input()->get('name', 'MaxPHP!'));
    }
}
