<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

echo <<<EOT
,--.  ,--.                   ,--.  ,------. ,--.  ,--.,------.
|  ,'.|  | ,---. ,--.  ,--.,-'  '-.|  .--. '|  '--'  ||  .--. '
|  |' '  || .-. : \  `'  / '-.  .-'|  '--' ||  .--.  ||  '--' |
|  | `   |\   --. /  /.  \   |  |  |  | --' |  |  |  ||  | --'
`--'  `--' `----''--'  '--'  `--'  `--'     `--'  `--'`--'

EOT;

$host = '0.0.0.0';
$port = '8989';
printf("System       Name:       %s\n", strtolower(PHP_OS));
printf("PHP          Version:    %s\n", PHP_VERSION);
printf("Listen       Addr:       %s\n", $host . ':' . $port);

passthru(PHP_BINARY . " -S $host:$port -t ./public ./server.php");
