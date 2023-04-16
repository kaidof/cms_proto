<?php

declare(strict_types=1);

namespace core\router;

class Request
{
    public static function uri()
    {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * @return bool
     */
    public function isJsonRequest()
    {
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }

    /**
     * Return POST param
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public function post($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Return GET param
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function json()
    {
        return json_decode($this->body(), true);
    }

    public function body()
    {
        return file_get_contents('php://input');
    }

    public function all()
    {
        return array_merge($_GET, $_POST);
    }

    public function has($key)
    {
        return isset($_GET[$key]) || isset($_POST[$key]);
    }

    /**
     * Return all params that match the key array
     *
     * @param array $keys
     * @return array
     */
    public function only($keys)
    {
        $result = [];

        foreach ($keys as $key) {
            if (isset($_GET[$key])) {
                $result[$key] = $_GET[$key];
            } elseif (isset($_POST[$key])) {
                $result[$key] = $_POST[$key];
            }
        }

        return $result;
    }

    /**
     * Is the given route name is the current request
     *
     * @param string $route
     * @return bool
     */
    public function routeIs($name)
    {
        return Router::currentRouteName() === $name;
    }
}
