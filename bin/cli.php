<?php

use App\Bootstrap;
use Max\Aop\Scanner;
use Max\Framework\Console\CommandCollector;
use Symfony\Component\Console\Application;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

(function() {
    $loader = require_once './vendor/autoload.php';
    Bootstrap::boot($loader, true);

    $config      = Scanner::scanConfig(BASE_PATH . '/vendor/composer/installed.json');
    $application = new Application();
    $commands    = array_merge($config['commands'], CommandCollector::all());
    foreach ($commands as $command) {
        $application->add(new $command);
    }
    $application->run();
})();

