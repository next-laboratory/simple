<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

return [
    'name'      => env('APP_NAME', 'marx'),
    'bindings'  => [
        \Psr\Log\LoggerInterface::class                           => \App\Logger::class,
        \Max\Config\Contract\ConfigInterface::class               => \Max\Config\Repository::class,
        \Max\Http\Server\Contract\HttpKernelInterface::class      => \App\Http\Kernel::class,
        \Max\Utils\Contract\PackerInterface::class                => \Max\Utils\Packer\JsonPacker::class,
        \Max\Http\Server\Contract\RouteDispatcherInterface::class => \Max\Http\Server\RouteDispatcher::class,
        \Psr\EventDispatcher\ListenerProviderInterface::class     => \Max\Event\ListenerProvider::class,
        \Psr\EventDispatcher\EventDispatcherInterface::class      => \Max\Event\EventDispatcher::class,
    ],
    'listeners' => [
        \App\Listener\DatabaseQueryListener::class,
    ],
];
