<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use App\Http\Response;
use Next\Utils\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Basic认证类
 * Apache下需要添加如下配置： RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}].
 */
class BasicAuthentication implements MiddlewareInterface
{
    /**
     * 需要进行验证的路径规则.
     */
    protected array $needAuth = ['*'];

    /**
     * 用户名密码对.
     */
    protected array $passwords = [
        'user' => 'password',
    ];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Collection::make($this->needAuth)->first(function ($pattern) use ($request) {
            return $request->is($pattern);
        })) {
            if ($header = $request->getHeaderLine('Authorization')) {
                [, $authorization] = explode(' ', $header, 2);
                [$user, $password] = explode(':', (string) base64_decode($authorization), 2);
                if ($this->shouldPass($user, $password)) {
                    return $handler->handle($request);
                }
            }
            return $this->shouldAuth();
        }
        return $handler->handle($request);
    }

    public function shouldPass(string $user, string $password): bool
    {
        return isset($this->passwords[$user]) && $this->passwords[$user] === $password;
    }

    protected function shouldAuth(): ResponseInterface
    {
        return new Response(401, [
            'WWW-Authenticate' => 'Basic realm="Input your ID and password"',
        ]);
    }
}
