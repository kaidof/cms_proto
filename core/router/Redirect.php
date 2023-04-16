<?php

declare(strict_types=1);

namespace core\router;

class Redirect
{
    private $path;

    public function __construct($path = null)
    {
        $this->path = $path;
    }

    public function route($name, $params = [])
    {
        $this->path = Router::getRoute($name, $params);

        return $this;
    }

    public static function to($path)
    {
        header('Location: ' . $path);
        exit();
    }

    public function with($key, $value)
    {
        $_SESSION['flash'][$key] = $value;

        return $this;
    }

    public function withErrors($errors)
    {
        $_SESSION['errors'] = $errors;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    /**
     * Create a new redirect response
     *
     * @return void
     */
    public function doAction()
    {
        $this->to($this->path);
    }
}
