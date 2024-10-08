<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

use Next\Config\Repository;
use Next\Di\Context;

if (function_exists('base_path') === false) {
    function base_path(string $path = ''): string
    {
        return BASE_PATH . ltrim($path, '/');
    }
}

if (function_exists('public_path') === false) {
    function public_path(string $path = ''): string
    {
        return base_path('public/' . ltrim($path, '/'));
    }
}

if (function_exists('config') === false) {
    function config(string $key, mixed $default = null): mixed
    {
        $config = Context::getContainer()->make(Repository::class);
        return $config->get($key, $default);
    }
}

if (function_exists('env') === false) {
    function env(string $key, $default = null): mixed
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
