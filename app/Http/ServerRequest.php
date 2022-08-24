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
use Max\Http\Message\ServerRequest as PsrServerRequest;
use Max\Session\Session;

class ServerRequest extends PsrServerRequest
{
    public function header(string $name): string
    {
        return $this->getHeaderLine($name);
    }

    /**
     * @throws Exception
     */
    public function session(): ?Session
    {
        if ($session = $this->getAttribute('Max\Session\Session')) {
            return $session;
        }
        throw new Exception('Session is not started');
    }

    public function server(string $name): ?string
    {
        return $this->getServerParams()[strtoupper($name)] ?? null;
    }

    public function raw(): string
    {
        return $this->getBody()->getContents();
    }

    /**
     * @deprecated
     */
    public function get(null|array|string $key = null, mixed $default = null): mixed
    {
        return $this->query(...func_get_args());
    }

    public function query(null|array|string $key = null, mixed $default = null): mixed
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

    public function all(): array
    {
        return $this->getQueryParams() + $this->getParsedBody();
    }

    /**
     * 获取客户端真实IP.
     */
    public function getRealIp(): string
    {
        if ($xForwardedFor = $this->getHeaderLine(HeaderInterface::HEADER_X_FORWARDED_FOR)) {
            $ips = explode(',', $xForwardedFor);
            return trim($ips[0]);
        }
        if ($xRealIp = $this->getHeaderLine('X-Real-IP')) {
            return $xRealIp;
        }
        $serverParams = $this->getServerParams();
        return $serverParams['remote_addr'] ?? '127.0.0.1';
    }

    protected function isEmpty(array $haystack, $needle): bool
    {
        return ! isset($haystack[$needle]) || $haystack[$needle] === '';
    }
}
