<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use App\Bootstrap;
use App\Console\Kernel;

require_once __DIR__ . DIRECTORY_SEPARATOR.'base.php';

(function () {
    $loader = require_once './vendor/autoload.php';
    Bootstrap::boot($loader, true);
    (new Kernel())->run();
})();
