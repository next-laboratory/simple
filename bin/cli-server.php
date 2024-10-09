<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

passthru(PHP_BINARY . ' -S 0.0.0.0:8989 -t ./public ./server.php');

