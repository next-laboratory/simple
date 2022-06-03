<?php

use Max\Config\Repository;
use Max\Di\Context;
use Max\Utils\Arr;
use Psr\Container\ContainerExceptionInterface;

if (false === function_exists('config')) {
    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    function config(string $key, $default = null): mixed
    {
        /** @var Repository $config */
        $config = Context::getContainer()->make(Repository::class);
        return $config->get($key, $default);
    }
}

if (false === function_exists('env')) {
    /**
     * @param string $key
     * @param        $default
     *
     * @return array|ArrayAccess|mixed
     */
    function env(string $key, $default = null): mixed
    {
        return Arr::get($_ENV, strtoupper($key), $default);
    }
}
