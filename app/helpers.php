<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use Max\Config\Repository;
use Max\Di\Context;
use Psr\Container\ContainerExceptionInterface;

if (function_exists('base_path') === false) {
    function base_path(string $path = ''): string
    {
        return BASE_PATH . ltrim($path, '/');
    }
}

if (function_exists('config') === false) {
    /**
     * @param mixed|null $default
     *
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    function config(string $key, mixed $default = null): mixed
    {
        /** @var Repository $config */
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
