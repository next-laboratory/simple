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

use App\Exceptions\CSRFException;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class VerifyCSRFToken implements MiddlewareInterface
{
    /**
     * 排除，不校验CSRF Token.
     */
    protected array $except = [
        '/'
    ];

    /**
     * @throws CSRFException
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->isMethod('POST') && !in_array($request->getUri()->getPath(), $this->except)) {
            $previousToken = $request->getCookieParams()['X-XSRF-TOKEN'] ?? null;
            if (is_null($previousToken)) {
                $this->abort();
            }

            $token = $request->getHeaderLine('X-CSRF-Token')
                ?: $request->getHeaderLine('X-XSRF-TOKEN')
                    ?: ($request->getParsedBody()['_token'] ?? null);
            if (is_null($token) || $token !== $previousToken) {
                $this->abort();
            }
        }

        return $handler->handle($request)->withCookie('X-XSRF-TOKEN', bin2hex(random_bytes(32)), time() + 9 * 3600);
    }

    /**
     * @throws CSRFException
     */
    protected function abort()
    {
        throw new CSRFException('CSRF token is invalid', 419);
    }
}
