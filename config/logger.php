<?php
declare(strict_types=1);

use Monolog\Logger;

return [
    'default' => 'app',
    'logger'  => [
        'app' => [
            'handler' => 'Monolog\Handler\RotatingFileHandler',
            'options' => [
                'filename' => __DIR__ . '/../runtime/logs/app.log',
                'maxFiles' => 180,
                'level'    => Logger::DEBUG,
            ],
        ],
        'sql' => [
            'handler' => 'Monolog\Handler\RotatingFileHandler',
            'options' => [
                'filename' => __DIR__ . '/../runtime/logs/database/sql.log',
                'maxFiles' => 180,
                'level'    => Logger::DEBUG,
            ],
        ],
    ],
];
