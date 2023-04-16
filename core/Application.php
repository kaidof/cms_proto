<?php

declare(strict_types=1);

namespace core;

class Application
{
    private static $instance;

    /**
     * Loaded active modules
     *
     * @var array
     */
    private array $modules = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        // start session
        // session_start();

        // set default timezone
        date_default_timezone_set(config()->get('app.timezone', 'UTC'));

        // set default locale
        // setlocale(LC_ALL, config()->get('app.locale', 'en_US'));

        /*
        // set default language
        $lang = config()->get('app.language', 'en');
        if (isset($_SESSION['lang'])) {
            $lang = $_SESSION['lang'];
        }
        $this->setLanguage($lang);
        */
    }

    /**
     * Get app environment or compare with given value
     *
     * @param ...$environments
     * @return bool|string
     */
    public function environment(...$environments)
    {
        $env = env()->get('ENVIRONMENT', 'production');

        if ($environments) {
            return in_array($env, $environments);
        }

        // default environment is production
        return $env;
    }

    public function isProduction()
    {
        return $this->environment('production');
    }

    public function isDevelopment()
    {
        return $this->environment('development');
    }

    public function isTesting()
    {
        return $this->environment('testing');
    }

    // not used yet
    public function setLanguage($lang)
    {
        $_SESSION['lang'] = $lang;
        putenv('LANG=' . $lang);
        setlocale(LC_ALL, $lang);

        // Choose domain
        // textdomain('messages');
    }

    /**
     * @param string $name
     * @param string $class
     *
     * @return void
     */
    public function addModule($name, $class)
    {
        $this->modules[$name] = $class;
    }

    /**
     * @return string[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isModuleActive($name)
    {
        return isset($this->modules[$name]);
    }
}
