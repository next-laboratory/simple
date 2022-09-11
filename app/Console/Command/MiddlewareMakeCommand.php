<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Console\Command;

use Exception;
use InvalidArgumentException;
use Max\Utils\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function App\base_path;

class MiddlewareMakeCommand extends Command
{
    protected string $stubsPath = __DIR__ . '/stubs/';

    protected function configure()
    {
        $this->setName('make:middleware')
            ->setDescription('Create a new middleware')
            ->setDefinition([
                new InputArgument('middleware', InputArgument::REQUIRED, 'A middleware name such as `auth`.'),
                new InputOption('suffix', 's', InputOption::VALUE_OPTIONAL, 'File is suffixed when this option is available.'),
            ]);
    }

    /**
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem               = new Filesystem();
        $stubFile                 = $this->stubsPath . 'middleware.stub';
        [$namespace, $middleware] = $this->parse($input->getArgument('middleware'));
        $middlewarePath           = base_path('app/Http/Middleware/' . str_replace('\\', '/', $namespace) . '/');
        $filesystem->exists($middlewarePath) || $filesystem->makeDirectory($middlewarePath, 0755, true);
        $suffix         = $input->getOption('suffix') ? 'Middleware' : '';
        $middlewareFile = $middlewarePath . $middleware . $suffix . '.php';
        $filesystem->exists($middlewareFile) && throw new InvalidArgumentException('中间件已经存在！');
        $filesystem->put($middlewareFile, str_replace(['{{namespace}}', '{{class}}'], ['App\\Http\\Middleware' . $namespace, $middleware . $suffix], file_get_contents($stubFile)));
        $output->writeln("<info>[DEBU]</info>中间件App\\Http\\Middleware{$namespace}\\{$middleware}创建成功！");

        return 1;
    }

    /**
     * @param $input
     */
    protected function parse($input): array
    {
        $array     = explode('/', $input);
        $class     = ucfirst(array_pop($array));
        $namespace = implode('\\', array_map(fn ($value) => ucfirst($value), $array));
        if (! empty($namespace)) {
            $namespace = '\\' . $namespace;
        }
        return [$namespace, $class];
    }
}
