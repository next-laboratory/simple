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

namespace App\Http;

use ArrayAccess;
use Max\Http\Message\Cookie;
use Max\Http\Message\Stream\FileStream;
use Max\Utils\Exceptions\FileNotFoundException;
use Max\Utils\Filesystem;
use Max\Utils\Str;
use Psr\Http\Message\ResponseInterface;
use Stringable;

class Response extends \Max\Http\Message\Response
{
    /**
     * Set cookie.
     */
    public function withCookie(
        string $name, string $value, int $expires = 3600, string $path = '/',
        string $domain = '', bool $secure = false, bool $httponly = false, string $samesite = ''
    ): static
    {
        $cookie = new Cookie(...func_get_args());
        return $this->withAddedHeader('Set-Cookie', $cookie->__toString());
    }

    /**
     * Create a JSON response.
     *
     * @param ArrayAccess|array $data
     */
    public static function JSON($data, int $status = 200): ResponseInterface
    {
        return new static($status, ['Content-Type' => 'application/json; charset=utf-8'], json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Create a HTML response.
     *
     * @param Stringable|string $data
     */
    public static function HTML($data, int $status = 200): ResponseInterface
    {
        return new static($status, ['Content-Type' => 'text/html; charset=utf-8'], (string)$data);
    }

    /**
     * Create a redirect response.
     */
    public static function redirect(string $url, int $status = 302): ResponseInterface
    {
        return new static($status, ['Location' => $url]);
    }

    /**
     * Create a file download response.
     *
     * @param string $uri    文件路径
     * @param string $name   文件名（留空则自动生成文件名）
     * @param int    $offset 偏移量
     * @param int    $length 长度
     *
     * @throws FileNotFoundException
     */
    public static function download(string $uri, string $name = '', int $offset = 0, int $length = -1): ResponseInterface
    {
        if (!file_exists($uri)) {
            throw new FileNotFoundException('File does not exist.');
        }
        if (empty($name)) {
            $extension = Filesystem::extension($uri);
            if (!empty($extension)) {
                $extension = '.' . $extension;
            }
            $name = Str::random(10) . $extension;
        }
        return new static(200, [
            'Content-Disposition' => sprintf('attachment;filename="%s"', htmlspecialchars($name, ENT_COMPAT))
        ], new FileStream($uri, $offset, $length));
    }
}
