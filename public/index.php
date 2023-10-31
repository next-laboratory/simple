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
use Next\Di\Context;
use Next\Http\Server\ResponseEmitter\FPMResponseEmitter;

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

(function () {
    require_once BASE_PATH . 'app/bootstrap.php';
    $kernel   = Context::getContainer()->make(Kernel::class);
    $response = $kernel->handle(ServerRequest::createFromGlobals());
    (new FPMResponseEmitter())->emit($response);
})();
