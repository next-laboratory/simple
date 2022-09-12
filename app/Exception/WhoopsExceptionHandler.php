<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Exception;

use Max\Http\Message\Contract\HeaderInterface;
use Max\Http\Message\Contract\StatusCodeInterface;
use Max\Http\Message\Response;
use Max\Http\Message\Stream\StandardStream;
use Max\Utils\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\XmlResponseHandler;
use Whoops\Run;
use Whoops\RunInterface;

class WhoopsExceptionHandler
{
    protected static array $preference = [
        'text/html'        => PrettyPageHandler::class,
        'application/json' => JsonResponseHandler::class,
        'application/xml'  => XmlResponseHandler::class,
    ];

    public function handle(Throwable $e, ServerRequestInterface $request): ?ResponseInterface
    {
        $whoops                  = new Run();
        [$handler, $contentType] = $this->negotiateHandler($request);

        $whoops->pushHandler($handler);
        $whoops->allowQuit(false);
        ob_start();
        $whoops->{RunInterface::EXCEPTION_HANDLER}($e);
        $content = ob_get_clean();

        return new Response(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR, [HeaderInterface::HEADER_CONTENT_TYPE => $contentType], StandardStream::create((string) $content));
    }

    protected function negotiateHandler(ServerRequestInterface $request): array
    {
        $accepts = $request->getHeaderLine(HeaderInterface::HEADER_ACCEPT);
        foreach (self::$preference as $contentType => $handler) {
            if (Str::contains($accepts, $contentType)) {
                return [$this->setupHandler(new $handler(), $request), $contentType];
            }
        }
        return [new PlainTextHandler(), 'text/plain'];
    }

    protected function setupHandler($handler, ServerRequestInterface $request)
    {
        if ($handler instanceof PrettyPageHandler) {
            $handler->handleUnconditionally(true);

            if (defined('BASE_PATH')) {
                $handler->setApplicationRootPath(BASE_PATH);
            }

            $handler->addDataTableCallback('GET Data', [$request, 'getQueryParams']);
            $handler->addDataTableCallback('POST Data', [$request, 'getParsedBody']);
            $handler->addDataTableCallback('Server/Request Data', [$request, 'getServerParams']);
            $handler->addDataTableCallback('Cookies', [$request, 'getCookieParams']);
            $handler->addDataTableCallback('Files', [$request, 'getUploadedFiles']);
            $handler->addDataTableCallback('Attribute', [$request, 'getAttributes']);
            try {
                $handler->addDataTableCallback('Session', [$request->session(), 'all']);
            } catch (Throwable) {
            }
        } elseif ($handler instanceof JsonResponseHandler) {
            $handler->addTraceToOutput(true);
        }

        return $handler;
    }
}
