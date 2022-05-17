<?php

namespace App\Http\Utils;

use InvalidArgumentException;
use Max\Context\Context;
use Max\Http\Message\UploadedFile;
use Max\Swoole\Http\ServerRequest as HttpServerRequest;
use Max\Routing\Route;
use Psr\Http\Message\ServerRequestInterface;
use Swoole\Http\Request;

class ServerRequest extends HttpServerRequest
{
    /**
     * @return Route
     */
    public function route()
    {
        return Context::get(Route::class);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function header(string $name): string
    {
        return $this->getHeaderLine($name);
    }

    /**
     * @param string $name
     *
     * @return ?string
     */
    public function server(string $name): ?string
    {
        return $this->getServerParams()[strtoupper($name)] ?? null;
    }

    /**
     * 请求类型判断
     *
     * @param string $method 请求类型
     *
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return 0 === strcasecmp($method, $this->getMethod());
    }

    /**
     * 获取请求的url
     *
     * @return string
     */
    public function url(): string
    {
        return $this->getUri()->__toString();
    }

    /**
     * 单个cookie
     *
     * @param string $name
     *
     * @return ?string
     */
    public function cookie(string $name): ?string
    {
        return $this->getCookieParams()[strtoupper($name)] ?? null;
    }

    /**
     * 判断是否ajax请求
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return 0 === strcasecmp('XMLHttpRequest', $this->getHeaderLine('X-REQUESTED-WITH'));
    }

    /**
     * 判断请求的地址是否匹配当前请求的地址
     *
     * @param string $path
     *
     * @return bool
     */
    public function is(string $path): bool
    {
        $requestPath = $this->getUri()->getPath();

        return 0 === strcasecmp($requestPath, $path) || preg_match("#^{$path}$#iU", $requestPath);
    }

    /**
     * get请求参数
     *
     * @param array|string|null $key     请求的参数列表
     * @param mixed             $default 字符串参数的默认值
     *
     * @return mixed
     */
    public function get(null|array|string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default, $this->getQueryParams());
    }

    /**
     * 获取POST参数
     *
     * @param array|string|null $key     请求的参数列表
     * @param mixed             $default 字符串参数的默认值
     *
     * @return mixed
     */
    public function post(null|array|string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default, $this->getParsedBody());
    }

    /**
     * GET + POST
     *
     * @return array
     */
    public function all(): array
    {
        return $this->getParsedBody() + $this->getQueryParams();
    }

    /**
     * 原始数据
     *
     * @return string
     */
    public function raw(): string
    {
        return $this->getPsr7()->getBody()->getContents();
    }

    /**
     * 判断请求的参数是不是空
     *
     * @param array $haystack
     * @param       $needle
     *
     * @return bool
     */
    protected function isEmpty(array $haystack, $needle): bool
    {
        return !isset($haystack[$needle]) || '' === $haystack[$needle];
    }

    /**
     * @param null       $key
     * @param null       $default
     * @param array|null $from
     *
     * @return mixed
     */
    public function input($key = null, mixed $default = null, ?array $from = null): mixed
    {
        $from ??= $this->all();

        if (is_null($key)) {
            return $from ?? [];
        }
        if (is_scalar($key)) {
            return $this->isEmpty($from, $key) ? $default : $from[$key];
        }
        if (is_array($key)) {
            $return = [];
            foreach ($key as $value) {
                $return[$value] = $this->isEmpty($from, $value) ? ($default[$value] ?? null) : $from[$value];
            }

            return $return;
        }
        throw new InvalidArgumentException('InvalidArgument！');
    }

    /**
     * @param string $field
     *
     * @return UploadedFile|null
     */
    public function file(string $field): ?UploadedFile
    {
        return $this->getUploadedFiles()[$field] ?? null;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     */
    public function setPsr7(ServerRequestInterface $serverRequest)
    {
        Context::put(ServerRequestInterface::class, $serverRequest);
    }

    /**
     * @return mixed
     */
    public function getSwooleRequest()
    {
        return Context::get(Request::class);
    }
}
