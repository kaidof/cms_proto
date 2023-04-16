<?php

declare(strict_types=1);

namespace core;

class Logger
{
    private $logFile = null;
    private $logLevel = null;
    private static $instance = null;

    // log level constants
    const DEBUG = 1;
    const INFO = 2;
    const WARNING = 4;
    const ERROR = 8;
    const CRITICAL = 16;
    const ALL = 31;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->logFile = constant('ROOT_DIR') . '/logs/' . date('Y-m-d') . '.log';
        $this->logLevel = (int)env()->get('LOG_LEVEL', self::CRITICAL | self::ERROR | self::WARNING);
    }

    /**
     * @param mixed $message
     * @param int $level
     * @return void
     */
    public function log($message, $level = self::DEBUG)
    {
        // bitwise AND log level comparison
        if ($this->logLevel & $level) {
            $this->write($message, $level);
        }
    }

    /**
     * @param mixed $message
     * @param int $level
     * @return void
     */
    private function write($message, $level = self::DEBUG)
    {
        // level int to string
        switch ($level) {
            case self::INFO:
                $levelStr = 'INFO';
                break;
            case self::WARNING:
                $levelStr = 'WARNING';
                break;
            case self::ERROR:
                $levelStr = 'ERROR';
                break;
            case self::CRITICAL:
                $levelStr = 'CRITICAL';
                break;
            default:
                $levelStr = 'DEBUG';
        }

        if (is_array($message) && count($message) === 1) {
            $message = $message[0];
        }

        if (is_array($message) || is_object($message)) {
            // if message is an array or object, convert to string
            // $message = print_r($message, true);

            // loop over array or object and add timestamp
            $message = array_map(function ($line) {
                return print_r($line, true);
            }, (array)$message);

            // join lines with new line
            $message = implode(PHP_EOL, $message);
        }

        $log = date('Y-m-d H:i:s') . ' [' . $levelStr . '] ' . $message . PHP_EOL;
        file_put_contents($this->logFile, $log, FILE_APPEND);
    }

    public function debug(...$message)
    {
        $this->log($message);
    }

    public function info(...$message)
    {
        $this->log($message, self::INFO);
    }

    public function warning(...$message)
    {
        $this->log($message, self::WARNING);
    }

    public function error(...$message)
    {
        $this->log($message, self::ERROR);
    }

    public function critical(...$message)
    {
        $this->log($message, self::CRITICAL);
    }
}
