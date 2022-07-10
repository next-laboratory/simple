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
use App\Console\Kernel;

require_once __DIR__ . DIRECTORY_SEPARATOR.'base.php';

(function() {
    $loader = require_once './vendor/autoload.php';
    Bootstrap::boot($loader, true);
    (new Kernel())->run();
})();

