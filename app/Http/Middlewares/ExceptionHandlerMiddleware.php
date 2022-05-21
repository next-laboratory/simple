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

use Max\Di\Annotations\Inject;
use Max\Framework\Http\Middlewares\ExceptionHandlerMiddleware as CoreExceptionHandlerMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class ExceptionHandlerMiddleware extends CoreExceptionHandlerMiddleware
{
    /**
     * @var LoggerInterface|mixed|object
     */
    #[Inject]
    protected LoggerInterface $logger;

    /**
     * @param Throwable              $throwable
     * @param ServerRequestInterface $request
     *
     * @return void
     */
    protected function reportException(Throwable $throwable, ServerRequestInterface $request): void
    {
        $this->logger->error($throwable->getMessage(), [
            'trace'   => $throwable->getTrace(),
            'method'  => $request->getMethod(),
            'uri'     => $request->getUri()->__toString(),
            'request' => $request->getQueryParams() + $request->getParsedBody(),
            'headers' => $request->getHeaders(),
            'file: '  => $throwable->getFile(),
            'line: '  => $throwable->getLine(),
            'code: '  => $throwable->getCode(),
        ]);
    }

    /**
     * @param Throwable              $throwable
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws Throwable
     */
    protected function renderException(Throwable $throwable, ServerRequestInterface $request): ResponseInterface
    {
        $this->dump($throwable);
        if ($request->isAjax()) {
            return $this->response->json([
                'status'  => false,
                'data'    => $throwable->getTrace(),
                'message' => $throwable->getMessage(),
                'code'    => $throwable->getCode(),
            ]);
        }
        return parent::renderException($throwable, $request);
    }

    /**
     * @param Throwable $throwable
     *
     * @return void
     */
    protected function dump(Throwable $throwable): void
    {
        (new ConsoleOutput())->writeln(sprintf("<comment>[ERRO]</comment> %s in %s +%d\n%s",
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            $throwable->getTraceAsString()
        ));
    }
}
