<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use App\Http\Kernel;
use App\Http\ServerRequest;
use Max\Di\Context;
use Max\Http\Server\ResponseEmitter\FPMResponseEmitter;

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');

(function () {
    require_once __DIR__ . '/../app/bootstrap.php';
    $kernel   = Context::getContainer()->make(Kernel::class);
    $response = $kernel->handle(ServerRequest::createFromGlobals());
    (new FPMResponseEmitter())->emit($response);
})();
