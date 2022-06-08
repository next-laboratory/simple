<?php

declare(strict_types=1);

/**
 * This file is part of the Max package.
 *
 * (c) Cheng Yao <987861463@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Bootstrap;
use App\Http\Kernel;
use App\Http\ServerRequest;
use Max\Di\Context;
use Max\Http\Server\ResponseEmitter\FPMResponseEmitter;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('memory_limit', '1G');
error_reporting(E_ALL);
date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

(function() {
    $loader = require_once '../vendor/autoload.php';
    Bootstrap::boot($loader, false);
    /** @var Kernel $kernel */
    $kernel   = Context::getContainer()->make(Kernel::class);
    $response = $kernel->through(ServerRequest::createFromGlobals());
    (new FPMResponseEmitter())->emit($response);
})();


