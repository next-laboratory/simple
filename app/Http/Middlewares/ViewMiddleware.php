<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middlewares;

use Max\View\Renderer;
use Max\View\ViewFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ViewMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected ViewFactory $viewFactory
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $renderer = $this->viewFactory->getRenderer();
        $renderer->assign('request', $request);
        return $handler->handle($request->withAttribute(Renderer::class, $renderer));
    }
}
