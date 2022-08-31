<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http;

use Exception;
use Max\Http\Message\Contract\HeaderInterface;
use Max\Http\Message\Stream\FileStream;
use Max\Http\Server\Response as PsrResponse;
use Max\Utils\Exception\FileNotFoundException;
use Max\Utils\Str;
use Max\View\ViewFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * 渲染视图.
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
        $headers[HeaderInterface::HEADER_CONTENT_DISPOSITION] = sprintf('attachment;filename="%s"', htmlspecialchars($name, ENT_COMPAT));
        $headers                                              = array_merge(static::DEFAULT_DOWNLOAD_HEADERS, $headers);
        return new static(200, $headers, new FileStream($uri, $offset, $length));
    }
}
