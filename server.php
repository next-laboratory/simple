<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

if (is_file($_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME'])) {
    return false;
}
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';

require __DIR__ . 'public/index.php';
