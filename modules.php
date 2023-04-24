<?php

// init modules
foreach (config()->get('modules', []) as $name => $module) {
    $class = $module['init'] ?? null;
    if ($class && class_exists($class) && ($module['enabled'] ?? false)) {
        app()->addModule($name, $class);

        $class = new $class();
        $class->init();
    }
}

// load routes
require_once __DIR__ . '/config/routes.php';
