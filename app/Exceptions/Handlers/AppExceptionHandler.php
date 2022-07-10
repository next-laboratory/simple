<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Exceptions\Handlers;

use App\Http\Response;
use Max\Http\Server\Contracts\ExceptionHandlerInterface;
use Max\Http\Server\Contracts\StoppableExceptionHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class AppExceptionHandler implements ExceptionHandlerInterface, StoppableExceptionHandlerInterface
{
    public function handle(Throwable $throwable, ServerRequestInterface $request): ?ResponseInterface
    {
        $message = sprintf('%s: %s in %s +%d', $throwable::class, $throwable->getMessage(), $throwable->getFile(), $throwable->getLine());
        if ($request->isAjax()) {
            return Response::JSON([
                'status'  => false,
                'code'    => 500,
                'data'    => $throwable->getTrace(),
                'message' => 'Internal service error.',
            ]);
        }
        return Response::HTML(sprintf(
            <<<'EOT'
<html><head><title>%s</title></head><body><pre style="font-size: 1.5em; white-space: break-spaces"><p><b>%s</b></p><b>Stack Trace</b><br>%s</pre></body></html>
EOT
            ,
            $message,
            $message,
            $throwable->getTraceAsString(),
        ), 500);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
