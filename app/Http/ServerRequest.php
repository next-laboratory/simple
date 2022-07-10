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
use RuntimeException;

class ServerRequest extends PsrServerRequest
{
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
     * @return ?string
     */
    public function server(string $name): ?string
    {
        return $this->getServerParams()[strtoupper($name)] ?? null;
    }

    public function isMethod(string $method): bool
    {
        return strcasecmp($this->getMethod(), $method) === 0;
    }

    public function url(): string
    {
        return $this->getUri()->__toString();
    }

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

    /**
     * @param null|array|string $key
     */
    public function get(null|array|string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default, $this->getQueryParams());
    }

    /**
     * @param null|array|string $key
     */
    public function post(null|array|string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default, $this->getParsedBody());
    }

    /**
     * @param null|array|string $key
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

    protected function isEmpty(array $haystack, $needle): bool
    {
        return !isset($haystack[$needle]) || $haystack[$needle] === '';
    }
}
