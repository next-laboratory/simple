<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App\Console\Command\Internal;

use Next\Utils\Exception\FileNotFoundException;
use Next\Utils\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerMakeCommand extends Command
{
    protected string $stubsPath = __DIR__ . '/stubs/';

    protected function configure()
    {
        $this->setName('make:controller')
             ->setDescription('Make a new controller')
             ->setDefinition([
                 new InputArgument('controller', InputArgument::REQUIRED, 'A controller name such as `user`.'),
                 new InputOption('rest', 'r', InputOption::VALUE_OPTIONAL, 'Make a restful controller.'),
             ]);
    }

    /**
     * @return int
     * @throws FileNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = new Filesystem();
        $controller = $input->getArgument('controller');
        $stubFile   = $this->stubsPath . ($input->getOption('rest') ? 'controller_rest.stub' : 'controller.stub');
        [$namespace, $controller] = $this->parse($controller);
        $controllerPath = base_path('app/Http/Controller/' . str_replace('\\', '/', $namespace) . '/');
        $controllerFile = $controllerPath . $controller . 'Controller.php';
        if ($filesystem->exists($controllerFile)) {
            $output->writeln('<comment>[WARN]</comment> 控制器已经存在!');
            return 1;
        }
        $filesystem->exists($controllerPath) || $filesystem->makeDirectory($controllerPath, 0777, true);
        $filesystem->put($controllerFile, str_replace(['{{namespace}}', '{{class}}', '{{path}}'], ['App\\Http\\Controller' . $namespace, $controller . 'Controller', strtolower($controller)], $filesystem->get($stubFile)));
        $output->writeln("<info>[INFO]</info> 控制器App\\Http\\Controller{$namespace}\\{$controller}Controller创建成功！");

        return 1;
    }

    protected function parse($input): array
    {
        $array     = explode('/', $input);
        $class     = ucfirst(array_pop($array));
        $namespace = implode('\\', array_map(fn($value) => ucfirst($value), $array));
        if (!empty($namespace)) {
            $namespace = '\\' . $namespace;
        }
        return [$namespace, $class];
    }
}
