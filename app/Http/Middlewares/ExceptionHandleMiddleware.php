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

use App\Http\Response;
use Max\Http\Server\Middlewares\ExceptionHandleMiddleware as HttpExceptionHandleMiddleware;
use Max\Utils\Str;
use Max\View\Renderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandleMiddleware extends HttpExceptionHandleMiddleware
{
    public function __construct(
        protected LoggerInterface $logger,
        protected ?Renderer       $renderer = null
    )
    {
    }

    public function handleException(Throwable $throwable, ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->error(sprintf('[%s] %s: %s', $requestId = Str::uuid(), $throwable::class, $throwable->getMessage()), [
            'file'    => $throwable->getFile(),
            'line'    => $throwable->getLine(),
            'headers' => $request->getHeaders(),
            'request' => $request->getQueryParams() + $request->getParsedBody(),
        ]);

        if (env('APP_DEBUG') || is_null($this->renderer)) {
            return parent::renderException(...func_get_args());
        }
        $code    = $this->getStatusCode($throwable);
        $message = $throwable->getMessage();
        return Response::HTML(<<<EOT
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Error $code</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            background: #eee;
            font-family: monospace;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .box {
            background: #fff;
            border-radius: 3px;
            padding: 20px 30px;
            max-width: 600px;
            flex-grow: 1;
            font-size: 14px;
            color: #333;
            margin: 0 auto; 
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
        }

        h1 {
            color: #111;
            font-size: 20px;
            padding: 0 0 0 0;
            margin: 10px 0 8px 0;
        }

        ul {
            padding: 0 0 0 10px;
        }

        li {
            list-style-type: none;
        }

        li::before {
            content: '- ';
        }

        button {
            box-sizing: content-box;
            width: 100%;
            display: block;
            background: #48e;
            color: #fff;
            border-radius: 0 0 2px 2px;
            border: 0;
            padding: 15px 30px;
            margin: 20px -30px -20px -30px;
            font-weight: bold;
            font-family: monospace;
        }

        button:hover {
            background: #37d;
        }

        button:focus {
            outline: 0;
        }

        button:focus:not(:hover) {
            outline: 0;
            border: 2px solid #25d;
            padding: 13px 28px;
        }

        button:active {
            outline: 0;
            box-shadow: 0 0 6px rgba(0,0,0,.3) inset;
            border: 0;
            padding: 15px 30px;
        }
    </style>
</head>
<body>
    <div class="box">
        <header>
            <h1>Error $code</h1>
        </header>
        <main>
            <p>$message</p>
            <ul>
                <li>Try again later.</li>
                <li>Check if you visited the correct URL.</li>
                <li>Report this issue if you think this is a mistake.</li>
            </ul>
            ID: $requestId
            <button type="button" onclick="window.location.reload()">Retry</button>
        </main>
    </div>
</body></html>
EOT
            , $code);
    }
}
