<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http;

use App\Models\User;
use Max\Http\Message\ServerRequest as PsrServerRequest;
use Max\Http\Message\UploadedFile;
use Max\JWT\Contracts\Authenticatable;
use Max\Session\Session;
use Max\Utils\Arr;
use Max\View\Renderer;
use RuntimeException;

class ServerRequest extends PsrServerRequest
{
    public function header(string $name): string
    {
        return $this->getHeaderLine($name);
    }

    public function session(): ?Session
    {
        if ($session = $this->getAttribute('Max\Session\Session')) {
            return $session;
        }
        throw new RuntimeException('Session is invalid.');
    }

    public function server(string $name): ?string
    {
        return $this->getServerParams()[strtoupper($name)] ?? null;
    }

    /**
     * Example: $request->isMethod('GET').
     */
    public function isMethod(string $method): bool
    {
        return strcasecmp($this->getMethod(), $method) === 0;
    }

    public function url(): string
    {
        return $this->getUri()->__toString();
    }

    /**
     * Example: $request->cookie('session_id').
     */
    public function cookie(string $name): ?string
    {
        return $this->getCookieParams()[strtoupper($name)] ?? null;
    }

    public function isAjax(): bool
    {
        return strcasecmp('XMLHttpRequest', $this->getHeaderLine('X-REQUESTED-WITH')) === 0;
    }

    public function isPath(string $path): bool
    {
        $requestPath = $this->getUri()->getPath();

        return strcasecmp($requestPath, $path) === 0 || preg_match("#^{$path}$#iU", $requestPath);
    }

    public function raw(): string
    {
        return $this->getBody()->getContents();
    }

    public function get(null|array|string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default, $this->getQueryParams());
    }

    public function post(null|array|string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default, $this->getParsedBody());
    }

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

    public function all(): array
    {
        return $this->getQueryParams() + $this->getParsedBody();
    }

    /**
     * @return ?User
     */
    public function user(): ?Authenticatable
    {
        return $this->getAttribute(User::class);
    }

    /**
     * @return Renderer
     */
    public function renderer()
    {
        return $this->getAttribute(Renderer::class);
    }

    /**
     * 获取客户端真实IP.
     */
    public function getRealIp(): string
    {
        $headers = $this->getHeaders();
        if (isset($headers['x-forwarded-for'][0]) && ! empty($headers['x-forwarded-for'][0])) {
            return $headers['x-forwarded-for'][0];
        }
        if (isset($headers['x-real-ip'][0]) && ! empty($headers['x-real-ip'][0])) {
            return $headers['x-real-ip'][0];
        }
        $serverParams = $this->getServerParams();

        return $serverParams['remote_addr'] ?? '';
    }

    protected function isEmpty(array $haystack, $needle): bool
    {
        return ! isset($haystack[$needle]) || $haystack[$needle] === '';
    }
}
