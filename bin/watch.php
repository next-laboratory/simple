<?php

require_once './vendor/autoload.php';

if (!class_exists('Max\Watcher\Watcher')) {
    throw new Exception('You should install the package max/watcher using command \'composer require max/watcher\'');
}

$env = $argv[1] ?? throw new Exception('Please input the script name like \'swoole\' as the first argument in command line');

$progress = function () {
    proc_open(PHP_BINARY . ' bin/swoole.php', [], $pipes);
};

$progress();

$watchDirs = [
    __DIR__ . '/../app'
];

$driver = new \Max\Watcher\Driver\FindDriver($watchDirs, function () use ($progress) {
    posix_kill(file_get_contents(__DIR__ . '/../runtime/master.pid'), SIGKILL);

    $progress();
});

(new \Max\Watcher\Watcher($driver))->run();
