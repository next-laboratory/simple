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

use Exception;
use Max\Http\Message\UploadedFile;
use Max\Session\Session;
use Max\Utils\Arr;
use RuntimeException;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Workerman\Connection\TcpConnection;

class ServerRequest extends \Max\Http\Message\ServerRequest
{
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
     * @return ?Session
     */
    public function session(): ?Session
    {
        if ($session = $this->getAttribute('Max\Session\Session')) {
            return $session;
        }
        throw new RuntimeException('Session is invalid.');
    }

    /**
     * @throws Exception
     */
    public function CSRFToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->session()->set('_token', $token);
        return $token;
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
     * @param string $method
     *
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return 0 === strcasecmp($this->getMethod(), $method);
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->getUri()->__toString();
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function cookie(string $name): ?string
    {
        return $this->getCookieParams()[strtoupper($name)] ?? null;
    }

    /**
     * @return bool
     */
    public function isAjax(): bool
    {
        return 0 === strcasecmp('XMLHttpRequest', $this->getHeaderLine('X-REQUESTED-WITH'));
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function isPath(string $path): bool
    {
        $requestPath = $this->getUri()->getPath();

        return 0 === strcasecmp($requestPath, $path) || preg_match("#^{$path}$#iU", $requestPath);
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        return $this->getBody()->getContents();
    }

    /**
     * @param array|string|null $key
     * @param mixed|null        $default
     *
     * @return mixed
     */
    public function get(null|array|string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default, $this->getQueryParams());
    }

    /**
     * @param array|string|null $key
     * @param mixed|null        $default
     *
     * @return mixed
     */
    public function post(null|array|string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default, $this->getParsedBody());
    }

    /**
     * @param array|string|null $key
     * @param mixed|null        $default
     * @param array|null        $from
     *
     * @return mixed
     */
    public function input(null|array|string $key = null, mixed $default = null, ?array $from = null): mixed
    {
        $from ??= $this->all();
        if (is_null($key)) {
            return $from ?? [];
        }
        if (is_array($key)) {
            $return = [];
            foreach ($key as $value) {
                $return[$value] = $this->isEmpty($from, $value) ? ($default[$value] ?? null) : $from[$value];
            }

            return $return;
        }
        return $this->isEmpty($from, $key) ? $default : $from[$key];
    }

    /**
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
     * @return null|Request|\Workerman\Protocols\Http\Request
     */
    public function rawRequest()
    {
        return $this->getAttribute('rawRequest');
    }

    /**
     * @return null|Response|TcpConnection
     */
    public function rawResponse()
    {
        return $this->getAttribute('rawResponse');
    }

    /**
     * @param string $field
     *
     * @return UploadedFile|null
     */
    public function file(string $field): ?UploadedFile
    {
        return Arr::get($this->files(), $field);
    }

    /**
     * @return UploadedFile[]
     */
    public function files(): array
    {
        return $this->getUploadedFiles();
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->getQueryParams() + $this->getParsedBody();
    }
}
