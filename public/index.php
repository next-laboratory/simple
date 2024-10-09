<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

use App\Http\Kernel;
use App\Http\ServerRequest;
use Next\Http\Server\FPMResponseEmitter;

require_once BASE_PATH . 'vendor/autoload.php';

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

(new FPMResponseEmitter())->emit((new Kernel())->handle(ServerRequest::createFromGlobals()));
