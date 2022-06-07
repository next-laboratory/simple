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

use Max\HttpMessage\Cookie;
use Max\Session\SessionManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{
    /**
     * Cookie 过期时间【+9小时，实际1小时后过期，和时区有关】
     */
    protected int    $expires  = 9 * 3600;
    protected string $name     = 'MAXPHP_SESSION_ID';
    protected bool   $httponly = true;
    protected string $path     = '/';
    protected string $domain   = '';
    protected bool   $secure   = true;

    public function __construct(protected SessionManager $sessionManager)
    {
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $this->sessionManager->create();
        $session->start($request->getCookieParams()[strtoupper($this->name)] ?? null);
        $request  = $request->withAttribute('Max\Session\Session', $session);
        $response = $handler->handle($request);
        $session->save();
        $session->close();
        $cookie = new Cookie(
            $this->name, $session->getId(), time() + $this->expires, $this->path, $this->domain, $this->secure, $this->httponly
        );

        return $response->withAddedHeader('Set-Cookie', $cookie->__toString());
    }
}
