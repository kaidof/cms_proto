<?php

declare(strict_types=1);

namespace core;

class Env
{
    /**
     * @var null | Env
     */
    private static $instance = null;

    private function __construct()
    {
        $this->loadEnv();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Load .env file.
     *
     * @return void
     */
    private function loadEnv()
    {
        $envFile = realpath(__DIR__ . '/../.env');

        if ($envFile !== false && file_exists($envFile)) {
            $env = file_get_contents($envFile);
            $env = explode("\n", $env);

            foreach ($env as $line) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                // skip comments
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }

                $parts = explode('=', $line);
                $key = trim($parts[0]);
                $value = trim($parts[1]);

                if (empty($key)) {
                    continue;
                }

                if (empty($value)) {
                    continue;
                }

                // remove outer surrounding quotes
                if (substr($value, 0, 1) === '"' && substr($value, -1, 1) === '"') {
                    $value = substr($value, 1, -1);
                }

                if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                    putenv(sprintf('%s=%s', $key, $value));
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        } else {
            throw new \InvalidArgumentException('Environment file does not exist');
        }
    }


    /**
     * Get environment variable.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (array_key_exists($name, $_ENV)) {
            return $_ENV[$name];
        }

        return $default;
    }
}
