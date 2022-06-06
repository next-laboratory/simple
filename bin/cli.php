<?php

$host = '0.0.0.0';
$port = 8989;
echo <<<EOT
,--.   ,--.                  ,------. ,--.  ,--.,------.  
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. ' 
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' | 
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'  
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--' 

EOT;
printf("System       Name:       %s\n", strtolower(PHP_OS));
printf("PHP          Version:    %s\n", PHP_VERSION);
passthru('php -S ' . $host . ':' . $port . ' -t public/ server.php');
