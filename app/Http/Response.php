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
use Max\Http\Message\Contract\HeaderInterface;
use Max\Http\Message\Response as PsrResponse;
use Max\Http\Message\Stream\FileStream;
use Max\Utils\Exception\FileNotFoundException;
use Max\Utils\Str;
use Max\View\ViewFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stringable;

use function Max\Utils\data_to_xml;

class Response extends PsrResponse
{
    protected const DEFAULT_DOWNLOAD_HEADERS = [
        HeaderInterface::HEADER_PRAGMA                    => 'public', // Public指示响应可被任何缓存区缓存
        HeaderInterface::HEADER_EXPIRES                   => '0', // 浏览器不会响应缓存
        HeaderInterface::HEADER_CACHE_CONTROL             => 'must-revalidate, post-check=0, pre-check=0',
        HeaderInterface::HEADER_CONTENT_TYPE              => 'application/download',
        HeaderInterface::HEADER_CONTENT_TRANSFER_ENCODING => 'binary',
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
     * Create a JSONP response.
     *
     * @param array|ArrayAccess $data
     */
    public static function JSONP(ServerRequestInterface $request, $data, int $status = 200): ResponseInterface
    {
        if ($callback = $request->input('callback')) {
            return new static(
                $status,
                [HeaderInterface::HEADER_CONTENT_TYPE => 'application/javascript; charset=utf-8'],
                sprintf('%s(%s)', $callback, json_encode($data, JSON_UNESCAPED_UNICODE))
            );
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
        return new static($status, [HeaderInterface::HEADER_CONTENT_TYPE => 'text/html; charset=utf-8'], (string)$data);
    }

    /**
     * Create a text response.
     */
    public static function text(string $content, int $status = 200): ResponseInterface
    {
        return new static($status, [HeaderInterface::HEADER_CONTENT_TYPE => 'text/plain; charset=utf-8'], $content);
    }

    /**
     * Create a XML response.
     */
    public static function XML(iterable $data, string $encoding = 'utf-8', string $root = 'root', int $status = 200): ResponseInterface
    {
        $xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
        $xml .= '<' . $root . '>';
        $xml .= data_to_xml($data);
        $xml .= '</' . $root . '>';
        return new static($status, [HeaderInterface::HEADER_CONTENT_TYPE => 'application/xml; charset=utf-8'], $xml);
    }

    /**
     * 渲染视图
     */
    public static function view(ServerRequestInterface $request, string $view, array $arguments = []): ResponseInterface
    {
        $renderer = make(ViewFactory::class)->getRenderer();
        $renderer->assign('request', $request);
        return Response::HTML($renderer->render($view, $arguments));
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
        if (!file_exists($uri)) {
            throw new FileNotFoundException('File does not exist.');
        }
        if (empty($name)) {
            $extension = pathinfo($uri, PATHINFO_EXTENSION);
            if (!empty($extension)) {
                $extension = '.' . $extension;
            }
            $name = Str::random(10) . $extension;
        }
        $headers[HeaderInterface::HEADER_CONTENT_DISPOSITION] = sprintf('attachment;filename="%s"', htmlspecialchars($name, ENT_COMPAT));
        $headers                                              = array_merge(static::DEFAULT_DOWNLOAD_HEADERS, $headers);
        return new static(200, $headers, new FileStream($uri, $offset, $length));
    }
}
