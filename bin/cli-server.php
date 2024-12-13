<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

date_default_timezone_set('PRC');
define('BASE_PATH', dirname(__DIR__) . '/');
require_once BASE_PATH . 'app/bootstrap.php';

echo <<<EOT
,--.  ,--.                   ,--.  ,------. ,--.  ,--.,------.
|  ,'.|  | ,---. ,--.  ,--.,-'  '-.|  .--. '|  '--'  ||  .--. '
|  |' '  || .-. : \  `'  / '-.  .-'|  '--' ||  .--.  ||  '--' |
|  | `   |\   --. /  /.  \   |  |  |  | --' |  |  |  ||  | --'
`--'  `--' `----''--'  '--'  `--'  `--'     `--'  `--'`--'

EOT;

$host = env('SERVER_HOST', '0.0.0.0');
$port = (int)env('SERVER_PORT', 8989);
printf("System       Name:       %s\n", strtolower(PHP_OS));
printf("PHP          Version:    %s\n", PHP_VERSION);
printf("Listen       Addr:       %s\n", $host . ':' . $port);

passthru(PHP_BINARY . " -S $host:$port -t ./public ./server.php");
