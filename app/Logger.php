<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

namespace App;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MonoLogger;
use Psr\Log\LoggerInterface;

class Logger implements LoggerInterface
{
    /** @var string 默认log */
    protected string $default = 'app';

    /** @var array 所有log */
    protected array $logger = [];

    public function __construct()
    {
        $this->logger[$this->default] = new MonoLogger($this->default, [
            new RotatingFileHandler(base_path('runtime/logs/' . $this->default . '.log'), 180, MonoLogger::DEBUG),
        ]);
        $this->logger['sql']          = new MonoLogger('sql', [
            new RotatingFileHandler(base_path('runtime/logs/database/sql.log'), 180, MonoLogger::DEBUG),
        ]);
    }

    /**
     * 返回一个logger.
     *
     * @param string $name 注册的名字
     */
    public function get(string $name = ''): MonoLogger
    {
        return $this->logger[$name ?: $this->default] ?? throw new \InvalidArgumentException('Logger ' . $name . ' does not exist');
    }

    public function emergency($message, array $context = []): void
    {
        $this->get()->emergency($message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->get()->alert($message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->get()->critical($message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->get()->error($message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->get()->warning($message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->get()->notice($message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->get()->info($message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->get()->debug($message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        $this->get()->log($level, $message, $context);
    }
}
