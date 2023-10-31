<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

return [
    'name'      => env('APP_NAME', 'marx'),
    'bindings'  => [
        \Psr\Log\LoggerInterface::class                            => \App\Logger::class,
        \Next\Config\Contract\ConfigInterface::class               => \Next\Config\Repository::class,
        \Next\Http\Server\Contract\HttpKernelInterface::class      => \App\Http\Kernel::class,
        \Next\Utils\Contract\PackerInterface::class                => \Next\Utils\Packer\JsonPacker::class,
        \Next\Http\Server\Contract\RouteDispatcherInterface::class => \Next\Http\Server\RouteDispatcher::class,
        \Psr\EventDispatcher\ListenerProviderInterface::class      => \Next\Event\ListenerProvider::class,
        \Psr\EventDispatcher\EventDispatcherInterface::class       => \Next\Event\EventDispatcher::class,
    ],
    'listeners' => [
        \App\Listener\DatabaseQueryListener::class,
    ],
];
