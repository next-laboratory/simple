<?php

namespace App\Controllers;

use Max\HttpServer\Context;
use Psr\Http\Message\ResponseInterface;

class IndexController
{
    public function index(Context $ctx): ResponseInterface
    {
        return $ctx->HTML('123')->withStatus(404);
    }
}
