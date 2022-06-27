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
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!in_array($request->getUri()->getPath(), $this->except) && $request->isMethod('POST')) {
            $token = $request->getHeaderLine('X-CSRF-Token') ?: $request->post('_token');
            if (is_null($token) || $request->session()->get('_token') !== $token) {
                throw new CSRFException('CSRF token is invalid', 419);
            }
        }
        $request->session()->remove('_token');
        return $handler->handle($request);
    }
}
