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

require_once __DIR__ . '/base.php';

(function () {
    Bootstrap::boot(true);
    (new Kernel())->run();
})();
