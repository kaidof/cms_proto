<?php

declare(strict_types=1);

namespace core;

class Config
{
    private static $config = [];
    private static $instance = null;

    private function __construct()
    {
        self::$config = include realpath(__DIR__ . '/../config/config.php');
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Return config value by key or default value.
     *
     * @param string $key param1.param2.param3 or param1|param2|param3
     * @param mixed $default null
     * @return mixed
     */
    public function get($key, $default = null)
    {
        // split string by . or |
        $dotKeys = explode('.', $key);

        $keys = $dotKeys;

        if (count($dotKeys) < 2) {
            // maybe we have a pipe
            $pipeKeys = explode('|', $key);
            if (count($pipeKeys) > 1) {
                // we have a pipe
                $keys = $pipeKeys;
            }
        }

        $current = &self::$config;
        while ($current && $k = array_shift($keys)) {
            if (isset($current[$k])) {
                $current = &$current[$k];
            } else {
                return $default;
            }
        }

        return $current;
    }

}
