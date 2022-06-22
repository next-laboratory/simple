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
        $this->logger['app'] = new MonoLogger('app', [
            new RotatingFileHandler(BASE_PATH . 'runtime/logs/app.log', 180, MonoLogger::DEBUG),
        ]);
        $this->logger['sql'] = new MonoLogger('sql', [
            new RotatingFileHandler(BASE_PATH . 'runtime/logs/database/sql.log', 180, MonoLogger::DEBUG),
        ]);
    }

    /**
     * 返回一个logger
     *
     * @param string $name 注册的名字
     *
     * @return MonoLogger
     */
    public function get(string $name = ''): MonoLogger
    {
        return $this->logger[$name ?: $this->default] ?? throw new \InvalidArgumentException('Logger not exist');
    }

    /**
     * @inheritdoc
     */
    public function emergency($message, array $context = array()): void
    {
        $this->get()->emergency($message, $context);
    }

    /**
     * @inheritdoc
     */
    public function alert($message, array $context = array()): void
    {
        $this->get()->alert($message, $context);
    }

    /**
     * @inheritdoc
     */
    public function critical($message, array $context = array()): void
    {
        $this->get()->critical($message, $context);
    }

    /**
     * @inheritdoc
     */
    public function error($message, array $context = array()): void
    {
        $this->get()->error($message, $context);
    }

    /**
     * @inheritdoc
     */
    public function warning($message, array $context = array()): void
    {
        $this->get()->warning($message, $context);
    }

    /**
     * @inheritdoc
     */
    public function notice($message, array $context = array()): void
    {
        $this->get()->notice($message, $context);
    }

    /**
     * @inheritdoc
     */
    public function info($message, array $context = array()): void
    {
        $this->get()->info($message, $context);
    }

    /**
     * @inheritdoc
     */
    public function debug($message, array $context = array()): void
    {
        $this->get()->debug($message, $context);
    }

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = array()): void
    {
        $this->get()->log($level, $message, $context);
    }
}
