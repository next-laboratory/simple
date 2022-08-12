<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http;

use ArrayAccess;
use Exception;
use Max\Http\Message\Response as PsrResponse;
use Max\Http\Message\Stream\FileStream;
use Max\Utils\Exception\FileNotFoundException;
use Max\Utils\Str;
use Psr\Http\Message\ResponseInterface;
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
     * Create a JSON response.
     *
     * @param array|ArrayAccess $data
     */
    public static function JSON($data, int $status = 200): ResponseInterface
    {
        return new static($status, ['Content-Type' => 'application/json; charset=utf-8'], json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Create a HTML response.
     *
     * @param string|Stringable $data
     */
    public static function HTML($data, int $status = 200): ResponseInterface
    {
        return new static($status, ['Content-Type' => 'text/html; charset=utf-8'], (string) $data);
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
     * Create a file download response.
     *
     * @param string $uri    文件路径
     * @param string $name   文件名（留空则自动生成文件名）
     * @param int    $offset 偏移量
     * @param int    $length 长度
     *
     * @throws FileNotFoundException
     * @throws Exception
     */
    public static function download(string $uri, string $name = '', array $headers = [], int $offset = 0, int $length = -1): ResponseInterface
    {
        if (! file_exists($uri)) {
            throw new FileNotFoundException('File does not exist.');
        }
        if (empty($name)) {
            $extension = pathinfo($uri, PATHINFO_EXTENSION);
            if (! empty($extension)) {
                $extension = '.' . $extension;
            }
            $name = Str::random(10) . $extension;
        }
        $headers['Content-Disposition'] = sprintf('attachment;filename="%s"', htmlspecialchars($name, ENT_COMPAT));
        $headers                        = array_merge(static::DEFAULT_DOWNLOAD_HEADERS, $headers);
        return new static(200, $headers, new FileStream($uri, $offset, $length));
    }
}
