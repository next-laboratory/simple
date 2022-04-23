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

namespace App\Middlewares;

use Max\Di\Exceptions\NotFoundException;
use Max\Http\Cookie;
use Max\Http\Exceptions\InvalidRequestHandlerException;
use Max\Http\Session;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;

class SessionMiddleware implements MiddlewareInterface
{
    /**
     * @var string|array|mixed|null
     */
    protected string $name = 'MAXPHP_SESSION_ID';

    /**
     * Cookie 过期时间【+9小时，实际1小时后过期，和时区有关】
     *
     * @var array|mixed|null
     */
    protected int $expires = 9 * 3600;

    /**
     * @var bool
     */
    protected bool $httponly = true;

    /**
     * @var string
     */
    protected string $path = '/';

    /**
     * @var string
     */
    protected string $domain = '';

    /**
     * @var bool
     */
    protected bool $secure = true;

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
     * @throws ContainerExceptionInterface
     * @throws InvalidRequestHandlerException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session->start($request->getCookieParams()[strtolower($this->name)] ?? null);
        $response = $handler->handle($request);
        $this->session->save();
        $this->session->close();
        $cookie = new Cookie(
            $this->name, $this->session->getId(), time() + $this->expires, $this->path, $this->domain, $this->secure, $this->httponly
        );
        return $response->withAddedHeader('Set-Cookie', $cookie->__toString());
    }
}
