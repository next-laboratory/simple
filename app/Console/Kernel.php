<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Console;

use App\Aop\Collector\CommandCollector;
use Next\Di\Context;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\Console\Application;

class Kernel extends Application
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function __construct(string $name = 'nextphp', string $version = '0.1')
    {
        parent::__construct($name, $version);
        $container = Context::getContainer();
        foreach ($this->commands() as $command) {
            $this->add($container->make($command));
        }
        if (class_exists('App\Aop\Collector\CommandCollector')) {
            foreach (CommandCollector::all() as $command) {
                $this->add($container->make($command));
            }
        }
    }

    protected function commands(): array
    {
        return [
            'App\Console\Command\Internal\SwooleServerCommand',
            'App\Console\Command\Internal\SwooleCoServerCommand',
            'App\Console\Command\Internal\CliServerCommand',
            'App\Console\Command\Internal\WorkermanServerCommand',
            'App\Console\Command\Internal\ControllerMakeCommand',
            'App\Console\Command\Internal\MiddlewareMakeCommand',
            'App\Console\Command\Internal\MiddlewareMakeCommand',
        ];
    }
}
