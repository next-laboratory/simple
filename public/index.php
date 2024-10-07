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

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');
require_once BASE_PATH . 'vendor/autoload.php';

(new Kernel())->handle(ServerRequest::createFromGlobals());
