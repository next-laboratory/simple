<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use App\Console\Kernel;
use Max\Aop\Aop;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

require_once BASE_PATH . 'app/bootstrap.php';

$aopConfig = config('aop');
Aop::init($aopConfig['scanDirs'], $aopConfig['collectors'], $aopConfig['runtimeDir']);

(new Kernel('MaxPHP', 'dev'))->run();
