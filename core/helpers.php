<?php

/**
 * This file holds helper functions
 */

if (!function_exists('dd')) {
    /**
     * Dump and die
     *
     * @param mixed $data
     */
    function dd(...$data)
    {
        echo '<pre>';

        foreach ($data as $item) {
            var_dump($item);
        }

        echo '</pre>';
        die();
    }
}

if (!function_exists('config')) {
    /**
     * Config object
     *
     * @return \core\Config
     */
    function config()
    {
        return \core\Config::getInstance();
    }
}

if (!function_exists('env')) {
    /**
     * Config object
     *
     * @return \core\Env
     */
    function env()
    {
        return \core\Env::getInstance();
    }
}

if (!function_exists('view')) {
    /**
     * Template object
     *
     * @param string $tpl
     * @param array $data
     *
     * @return \core\Template
     */
    function view($tpl, $data = [])
    {
        return new \core\Template($tpl, $data);
    }
}

if (!function_exists('request')) {
    /**
     * Request object
     *
     * @return \core\router\Request
     */
    function request()
    {
        return new \core\router\Request();
    }
}

if (!function_exists('response')) {
    /**
     * Response object
     *
     * @param mixed $data
     * @param int $status
     *
     * @return \core\router\Response
     */
    function response($data = null, $status = 200)
    {
        return new \core\router\Response($data, $status);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect object
     *
     * @return \core\router\Redirect
     */
    function redirect($path = null)
    {
        return new \core\router\Redirect($path);
    }
}

if (!function_exists('logger')) {
    /**
     * Logger
     *
     * @return \core\Logger
     */
    function logger()
    {
        return \core\Logger::getInstance();
    }
}

if (!function_exists('route')) {
    /**
     * Return route by name
     *
     * @param string $name
     * @param array $params
     *
     * @return string
     */
    function route($name, $params = [])
    {
        return \core\router\Router::getRoute($name, $params);
    }
}

if (!function_exists('url')) {
    /**
     * Url object
     *
     * @return \core\router\Url
     */
    function url()
    {
        return new \core\router\Url();
    }
}

if (!function_exists('asset')) {
    /**
     * Admin assets Url
     *
     * @return string
     */
    function asset($path)
    {
        return (new \core\router\Url())->getFullBaseUrl() . '/core/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('db')) {
    /**
     * Db object
     *
     * @return \core\Db
     */
    function db()
    {
        return \core\Db::getInstance();
    }
}

if (!function_exists('hook')) {
    /**
     * Hook object
     *
     * @return \core\HookManager
     */
    function hook()
    {
        return \core\HookManager::getInstance();
    }
}

if (!function_exists('assets')) {
    /**
     * Assets object
     *
     * @return \core\Assets
     */
    function assets()
    {
        return \core\Assets::getInstance();
    }
}

if (!function_exists('auth')) {
    /**
     * Authentication logic
     *
     * @return \core\AuthenticatedUser
     */
    function auth()
    {
        return \core\AuthenticatedUser::getInstance();
    }
}

if (!function_exists('app')) {
    /**
     * Application object
     *
     * @return \core\Application
     */
    function app()
    {
        return \core\Application::getInstance();
    }
}

if (!function_exists('view_set_layout')) {
    /**
     * Set the layout template for the current view
     *
     * @param string $tpl
     * @param array $data
     *
     * @return void
     */
    function view_set_layout($tpl, $data = [])
    {
        \core\Template::setLayout($tpl, $data);
    }
}

if (!function_exists('view_partial')) {
    /**
     * Render a partial view
     *
     * @param string $tpl
     * @param array $data
     *
     * @return void
     */
    function view_partial($tpl, $data = [])
    {
        \core\Template::renderPartial($tpl, $data);
    }
}

if (!function_exists('view_var')) {
    /**
     * Set view variable(s)
     *
     * @param mixed $name
     * @param mixed $value
     *
     * @return void
     */
    function view_var($name, $value = null)
    {
        \core\Template::setVariable($name, $value);
    }
}



/**
 * Global functions
 */

if (!function_exists('get_admin_module_menu')) {
    /**
     * Return module menu items
     *
     * @return array
     */
    function get_admin_module_menu()
    {
        $menuList = [];

        $menuList = (array)hook()->run('admin_module_menu', $menuList);

        // sort by order property
        usort($menuList, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return $menuList;
    }
}
