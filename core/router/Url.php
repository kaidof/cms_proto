<?php

declare(strict_types=1);

namespace core\router;

class Url
{

    /**
     * Return URL like http://site.com/path/to/page
     *
     * @return string
     */
    public function fullUrl()
    {
        return $this->getScheme() . '://' . $this->getHost() . $this->getUri();
    }

    /**
     * Return scheme like http or https
     *
     * @return string
     */
    public function getScheme()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
    }

    /**
     * Return host like site.com
     *
     * @return string
     */
    public function getHost()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Return URI like /path/to/page
     *
     * @return mixed
     */
    public function getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Return URL like http://site.com
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->getScheme() . '://' . $this->getHost();
    }

    /**
     * Return URL base path like /path/to
     *
     * @return string
     */
    public function getBasePath()
    {
        // base_path
        $path = config()->get('web_root', '');
        if ($path) {
            $path = '/' . trim($path, '/');
        }

        return $path === '/' ? '' : $path;
    }

    /**
     * Return URL like http://site.com/base
     *
     * @return string
     */
    public function getFullBaseUrl()
    {
        return $this->getBaseUrl() . $this->getBasePath();
    }

    /**
     * Return URL like http://site.com/path/to/page
     *
     * @return string
     */
    public function getFullUrl()
    {
        return $this->getFullBaseUrl() . $this->getUri();
    }

    /**
     * @return string|null
     */
    public function previous()
    {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }

    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public function isPut()
    {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }

    public function isDelete()
    {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }

    public function isPatch()
    {
        return $_SERVER['REQUEST_METHOD'] === 'PATCH';
    }

    public function isHead()
    {
        return $_SERVER['REQUEST_METHOD'] === 'HEAD';
    }

    public function isOptions()
    {
        return $_SERVER['REQUEST_METHOD'] === 'OPTIONS';
    }

    public function isSecure()
    {
        return $this->getScheme() == 'https';
    }

    public function isParam($name)
    {
        return isset($_REQUEST[$name]);
    }

    public function getParam($name, $default = null)
    {
        return $_REQUEST[$name] ?? $default;
    }

    public function admin($full = false)
    {
        if ($full) {
            return $this->getFullBaseUrl() . '/admin';
        } else {
            return $this->getBasePath() . '/admin';
        }
    }

    /**
     * Return the current URL without the query string
     *
     * @param string $name
     * @param array $params
     * @param bool $full
     * @return int|string|null
     */
    public function route($name, $params = [], $full = false)
    {
        $url = Router::getRoute($name, $params);
        if (!$url) {
            return null;
        }

        /*
        if ($full) {
            return $this->getFullBaseUrl() . $url;
        } else {
            return $this->getBasePath() . $url;
        }
        */

        if ($full) {
            $url = $this->getFullBaseUrl() . $url;
        }

        return $url;
    }

}