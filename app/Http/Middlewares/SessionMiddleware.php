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

use Max\Foundation\Http\Session;
use Max\Http\Exceptions\HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{
    /**
     * @var string|array|mixed|null
     */
    protected string $name = 'MAXPHP_SESSION_ID';

    /**
     * Cookie 过期时间
     *
     * @var array|mixed|null
     */
    protected int $cookieExpires = 3600;

    protected bool $httponly = true;

    protected bool $secure = true;

    protected array $cookieOptions = [
        'expires'  => 3600,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,
        'httponly' => false,
        'samesite' => '',
    ];

    /**
     * @param Session $session
     */
    public function __construct(protected Session $session)
    {
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws HttpException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $id = $request->getCookieParams()[$this->name] ?? $this->session->refreshId();
        $this->session->start($id);
        $response = $handler->handle($request);
        $this->session->save();
        $expires = time() + $this->cookieExpires;
        return $response->withAddedHeader('Set-Cookie', "$this->name=$id; expires=$expires");
    }
}
