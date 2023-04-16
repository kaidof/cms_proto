<?php

declare(strict_types=1);

namespace Core;

class Autoload
{
    public function __construct()
    {
        spl_autoload_register([$this, 'load']);
    }

    /**
     * @param string $class
     * @return void
     */
    public function load($class)
    {
        // load only classes from core and modules namespaces
        $isModule = strpos($class, 'modules\\') === 0;

        if (strpos($class, 'core\\') === 0 || $isModule) {
            $class = str_replace('\\', '/', $class);

            if ($isModule) {
                // FIXME: is this a good idea?
                // load modules classes, don't throw error if class not found
                include constant('ROOT_DIR') . '/' . $class . '.php';
            } else {
                // load core classes
                require constant('ROOT_DIR') . '/' . $class . '.php';
            }
        }
    }
}

new Autoload();
