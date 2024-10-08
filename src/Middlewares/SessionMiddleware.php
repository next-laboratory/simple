<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Middlewares;

use Next\Http\Message\Cookie;
use Next\Session\Handler\FileHandler;
use Next\Session\Session;
use Next\Utils\Contract\PackerInterface;
use Next\Utils\Packer\PhpSerializePacker;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 部分属性注释来自MDN: https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Cookies.
 */
class SessionMiddleware implements MiddlewareInterface
{
    /**
     * 会话期 Cookie 是最简单的 Cookie：浏览器关闭之后它会被自动删除，也就是说它仅在会话期内有效。会话期 Cookie 不需要指定过期时间（Expires）或者有效期（Max-Age）。需要注意的是，有些浏览器提供了会话恢复功能，这种情况下即使关闭了浏览器，会话期 Cookie 也会被保留下来，就好像浏览器从来没有关闭一样，这会导致 Cookie 的生命周期无限期延长。
     * 持久性 Cookie 的生命周期取决于过期时间（Expires）或有效期（Max-Age）指定的一段时间。
     */
    protected int $expires = 9 * 3600;

    /**
     * 会话Cookie名.
     */
    protected string $name = 'SESSION';

    /**
     * 有两种方法可以确保 Cookie 被安全发送，并且不会被意外的参与者或脚本访问：Secure 属性和HttpOnly 属性。
     * 标记为 Secure 的 Cookie 只应通过被 HTTPS 协议加密过的请求发送给服务端，因此可以预防 man-in-the-middle 攻击者的攻击。但即便设置了 Secure 标记，敏感信息也不应该通过 Cookie 传输，因为 Cookie 有其固有的不安全性，Secure 标记也无法提供确实的安全保障，例如，可以访问客户端硬盘的人可以读取它。
     * JavaScript Document.cookie API 无法访问带有 HttpOnly 属性的 cookie；此类 Cookie 仅作用于服务器。例如，持久化服务器端会话的 Cookie 不需要对 JavaScript 可用，而应具有 HttpOnly 属性。此预防措施有助于缓解跨站点脚本（XSS） (en-US)攻击。
     */
    protected bool $httponly = true;

    /**
     * Path 标识指定了主机下的哪些路径可以接受 Cookie（该 URL 路径必须存在于请求 URL 中）。以字符 %x2F ("/") 作为路径分隔符，子路径也会被匹配。
     *
     * 例如，设置 Path=/docs，则以下地址都会匹配：
     * /docs
     * /docs/Web/
     * /docs/Web/HTTP
     */
    protected string $path = '/';

    /**
     * Domain 指定了哪些主机可以接受 Cookie。如果不指定，默认为 origin，不包含子域名。
     * 如果指定了Domain，则一般包含子域名。因此，指定 Domain 比省略它的限制要少。但是，当子域需要共享有关用户的信息时，这可能会有所帮助。
     * 例如，如果设置 Domain=mozilla.org，则 Cookie 也包含在子域名中（如developer.mozilla.org）。
     */
    protected string $domain = '';

    /**
     * 标记为 Secure 的 Cookie 只应通过被 HTTPS 协议加密过的请求发送给服务端，因此可以预防 man-in-the-middle 攻击者的攻击。
     * 但即便设置了 Secure 标记，敏感信息也不应该通过 Cookie 传输，因为 Cookie 有其固有的不安全性，Secure 标记也无法提供确实的安全保障.
     *
     * 例如，可以访问客户端硬盘的人可以读取它。
     */
    protected bool $secure = false;

    /**
     * SameSite Cookie 允许服务器要求某个 cookie 在跨站请求时不会被发送，（其中 Site (en-US) 由可注册域定义），从而可以阻止跨站请求伪造攻击（CSRF）。
     * SameSite 可以有下面三种值：
     * None。浏览器会在同站请求、跨站请求下继续发送 cookies，不区分大小写。
     * Strict。浏览器将只在访问相同站点时发送 cookie。（在原有 Cookies 的限制条件上的加强，如上文 “Cookie 的作用域” 所述）
     * Lax。与 Strict 类似，但用户从外部站点导航至 URL 时（例如通过链接）除外。 在新版本浏览器中，为默认选项，Same-site cookies 将会为一些跨站子请求保留，如图片加载或者 frames 的调用，但只有当用户从外部站点导航到 URL 时才会发送。如 link 链接.
     */
    protected string $sameSite = Cookie::SAME_SITE_LAX;

    protected \SessionHandlerInterface $sessionHandler;

    protected PackerInterface $packer;

    public function __construct()
    {
        $this->sessionHandler = new FileHandler();
        $this->packer         = new PhpSerializePacker();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = new Session($this->sessionHandler, $this->packer);
        $session->start($request->getCookieParams()[strtoupper($this->name)] ?? '');
        $request  = $request->withAttribute('Next\Session\Session', $session);
        $response = $handler->handle($request);
        $session->save();
        $session->close();

        return $this->addCookieToResponse($response, $session);
    }

    /**
     * 将cookie添加到响应.
     */
    protected function addCookieToResponse(ResponseInterface $response, Session $session): ResponseInterface
    {
        $expires = $session->isDestroyed() ? -1 : time() + $this->expires;
        $cookie  = new Cookie($this->name, $session->getId(), $expires, $this->path, $this->domain, $this->secure, $this->httponly, $this->sameSite);

        return $response->withAddedHeader('Set-Cookie', $cookie->__toString());
    }
}
