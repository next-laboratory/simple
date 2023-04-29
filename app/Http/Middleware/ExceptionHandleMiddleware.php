<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use App\Http\Response;
use Max\Http\Server\Middleware\ExceptionHandleMiddleware as Middleware;
use Max\Routing\Exception\MethodNotAllowedException;
use Max\Routing\Exception\RouteNotFoundException;
use Max\VarDumper\Dumper;
use Max\VarDumper\DumperHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandleMiddleware extends Middleware
{
    use DumperHandler;

    protected array $dontReport = [
        Dumper::class,
        RouteNotFoundException::class,
        MethodNotAllowedException::class,
    ];

    public function __construct(
        protected LoggerInterface $logger,
    )
    {
    }

    protected function render(Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        return match (true) {
            $e instanceof Dumper => Response::HTML(self::convertToHtml($e)),
            env('APP_DEBUG') => parent::render($e, $request),
            default => Response::text($e->getMessage(), $this->getStatusCode($e)),
        };
    }

    protected function report(Throwable $e, ServerRequestInterface $request): void
    {
        $this->logger->error($e->getMessage(), [
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'request' => $request,
            'trace'   => $e->getTrace(),
        ]);
    }
}
