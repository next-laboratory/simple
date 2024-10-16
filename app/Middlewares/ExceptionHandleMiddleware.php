<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Middlewares;

use App\Logger;
use App\Response;
use Next\Http\Message\Contract\StatusCodeInterface;
use Next\Http\Message\Exception\HttpException;
use Next\Utils\Arr;
use Next\VarDumper\Dumper;
use Next\VarDumper\DumperHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExceptionHandleMiddleware implements MiddlewareInterface
{
    use DumperHandler;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected array  $dontReport = [];
    protected Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    /**
     * @throws \Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            set_error_handler(function ($errno, $errMsg, $errFile, $errLine) {
                throw new \ErrorException($errMsg, 0, $errno, $errFile, $errLine);
            });
            $response = $handler->handle($request);
            restore_error_handler();
            return $response;
        } catch (\Throwable $e) {
            if (!$this->shouldntReport($e)) {
                $this->report($e, $request);
            }
            return $this->render($e, $request);
        }
    }

    /**
     * 报告异常.
     */
    protected function report(\Throwable $e, ServerRequestInterface $request): void
    {
        $this->logger->error($e->getMessage(), [
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'request' => $request->all(),
            'trace'   => $e->getTrace(),
        ]);
    }

    /**
     * 将异常转为ResponseInterface对象
     */
    protected function render(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        return match (true) {
            $e instanceof Dumper => Response::HTML(self::convertToHtml($e)),
            env('APP_DEBUG')     => $this->defaultRender($e, $request),
            default              => Response::text($e->getMessage(), $this->getStatusCode($e)),
        };
    }

    protected function defaultRender(\Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        if (str_contains($request->getHeaderLine('Accept'), 'json')
            || strcasecmp('XMLHttpRequest', $request->getHeaderLine('X-REQUESTED-WITH')) === 0) {
            return new Response($this->getStatusCode($e), ['Content-Type' => 'application/json; charset=utf-8'], json_encode([
                'code'    => 0,
                'data'    => $e->getTrace(),
                'message' => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE));
        }

        return new Response($this->getStatusCode($e), ['Content-Type' => 'text/plain; charset=utf-8'], $e->getMessage());
    }

    protected function getStatusCode(\Throwable $e)
    {
        return $e instanceof HttpException ? $e->getStatusCode() : StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;
    }

    /**
     * 忽略报告的异常.
     */
    protected function shouldntReport(\Throwable $e): bool
    {
        return !is_null(Arr::first($this->dontReport, fn($type) => $e instanceof $type));
    }

    /**
     * 运行环境是否是cli.
     */
    protected function runningInConsole(): bool
    {
        return PHP_SAPI === 'cli';
    }
}
