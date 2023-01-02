<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http;

use JsonSerializable;
use Max\Http\Message\Cookie;
use Max\Http\Message\Response as PsrResponse;
use Max\Http\Message\Stream\FileStream;
use Max\Utils\Contract\Arrayable;
use Max\View\ViewFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stringable;

class Response extends PsrResponse
{
    protected const DEFAULT_DOWNLOAD_HEADERS = [
        'Pragma'                    => 'public', // Public指示响应可被任何缓存区缓存
        'Expires'                   => '0', // 浏览器不会响应缓存
        'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
        'Content-Type'              => 'application/download',
        'Content-Transfer-Encoding' => 'binary',
    ];

    /**
     * 渲染视图.
     *
     * @throws ContainerExceptionInterface
     * @throws \ReflectionException
     */
    public static function view(string $view, array $arguments = [], ?ServerRequestInterface $request = null): ResponseInterface
    {
        $renderer = make(ViewFactory::class)->getRenderer();
        if (isset($request)) {
            $renderer->assign('request', $request);
        }
        return Response::HTML($renderer->render($view, $arguments));
    }

    /**
     * Create a file download response.
     *
     * @param string $name   文件名（留空则自动生成文件名）
     * @param int    $offset 偏移量
     * @param int    $length 长度
     */
    public static function download(string $file, string $name = '', int $offset = 0, int $length = 0): ResponseInterface
    {
        $name                           = $name ?: pathinfo($file, PATHINFO_BASENAME);
        $headers                        = static::DEFAULT_DOWNLOAD_HEADERS;
        $headers['Content-Disposition'] = sprintf('attachment;filename="%s"', htmlspecialchars($name, ENT_COMPAT));
        return new static(200, $headers, new FileStream($file, $offset, $length));
    }

    /**
     * Create a JSON response.
     *
     * @param array|Arrayable|string|JsonSerializable $data
     */
    public static function JSON($data, int $status = 200): ResponseInterface
    {
        if (!is_string($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return new static($status, ['Content-Type' => 'application/json; charset=utf-8'], $data);
    }

    /**
     * Create a JSONP response.
     *
     * @param array|Arrayable $data
     */
    public static function JSONP(ServerRequestInterface $request, $data, int $status = 200): ResponseInterface
    {
        if ($callback = $request->query('callback')) {
            if (!is_string($data)) {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            }
            return new static($status, ['Content-Type' => 'application/javascript; charset=utf-8'], sprintf('%s(%s)', $callback, $data));
        }
        return static::JSON($data, $status);
    }

    /**
     * Create a HTML response.
     *
     * @param string|Stringable $data
     */
    public static function HTML($data, int $status = 200): ResponseInterface
    {
        return new static($status, ['Content-Type' => 'text/html; charset=utf-8'], (string)$data);
    }

    /**
     * Create a text response.
     */
    public static function text(string $content, int $status = 200): ResponseInterface
    {
        return new static($status, ['Content-Type' => 'text/plain; charset=utf-8'], $content);
    }

    /**
     * Create a redirect response.
     */
    public static function redirect(string $url, int $status = 302): ResponseInterface
    {
        return new static($status, ['Location' => $url]);
    }

    /**
     * Is the response empty?
     */
    public function isEmpty(): bool
    {
        return in_array($this->statusCode, [204, 304]);
    }

    /**
     * Set cookie.
     */
    public function withCookie(
        string $name,
        string $value,
        int    $expires = 3600,
        string $path = '/',
        string $domain = '',
        bool   $secure = false,
        bool   $httponly = false,
        string $sameSite = ''
    ): static
    {
        $cookie = new Cookie(...func_get_args());
        return $this->withAddedHeader('Set-Cookie', $cookie->__toString());
    }

    public function setCookie(
        string $name,
        string $value,
        int    $expires = 3600,
        string $path = '/',
        string $domain = '',
        bool   $secure = false,
        bool   $httponly = false,
        string $sameSite = ''
    ): static
    {
        $cookie = new Cookie(...func_get_args());
        return $this->setAddedHeader('Set-Cookie', $cookie->__toString());
    }
}
