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

namespace App\Http\Middlewares;

use Max\HttpMessage\Response;
use Max\HttpServer\Middlewares\ExceptionHandleMiddleware as HttpExceptionHandleMiddleware;
use Max\View\Renderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandleMiddleware extends HttpExceptionHandleMiddleware
{
    public function __construct(protected LoggerInterface $logger, protected ?Renderer $renderer = null)
    {
    }

    protected function reportException(Throwable $throwable, ServerRequestInterface $request): void
    {
        $this->logger->error($throwable->getMessage(), [
            'file'    => $throwable->getFile(),
            'line'    => $throwable->getLine(),
            'headers' => $request->getHeaders(),
            'request' => $request->getQueryParams() + $request->getParsedBody()
        ]);
    }

    protected function renderException(Throwable $throwable, ServerRequestInterface $request): ResponseInterface
    {
        if (env('APP_DEBUG') || is_null($this->renderer)) {
            return parent::renderException(...func_get_args());
        }
        return new Response($this->getStatusCode($throwable), [], $this->renderer->render('error'));
    }
}
