<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Command\Internal;

use Closure;
use Next\Di\Exception\NotFoundException;
use Next\Http\Server\Contract\HttpKernelInterface;
use Next\Routing\Route;
use Next\Utils\Collection;
use Psr\Container\ContainerExceptionInterface;
use ReflectionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class RouteListCommand extends Command
{
    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table(new ConsoleOutput());
        $table->setHeaders(['Methods', 'URI', 'Action', 'Middlewares']);
        foreach ($this->getRoutes() as $route) {
            $table->addRow([
                implode('|', $route->getMethods()),
                $route->getPath(),
                $this->formatRouteAction($route),
                implode(PHP_EOL, $route->getMiddlewares()),
            ]);
        }
        $table->render();
        return 0;
    }

    protected function configure()
    {
        $this->setName('route:list')
             ->setDescription('List all routes');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws ReflectionException
     */
    protected function getRoutes(): Collection
    {
        $kernel = make(HttpKernelInterface::class);
        $routes = [];
        foreach ($kernel->getAllRoutes() as $registeredRoute) {
            foreach ($registeredRoute as $route) {
                if (!in_array($route, $routes)) {
                    $routes[] = $route;
                }
            }
        }
        return Collection::make($routes)->unique();
    }

    /**
     * 格式化action为string.
     */
    protected function formatRouteAction(Route $route): string
    {
        $action = $route->getAction();
        if ($action instanceof Closure) {
            return 'Closure';
        }
        if (is_array($action)) {
            return implode('@', $action);
        }
        return $action;
    }
}
