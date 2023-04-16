<?php

use core\router\Router;


Router::add('GET', '/', function() {
    return '<h1>Home page</h1>'
        . '<h2>User is ' . (auth()->isLoggedIn() ? 'logged in (' . auth()->getName() . ')' : 'not logged in') . '</h2>'
        . '<a href="' . url()->route('admin.index') . '">Admin</a>'
        . '<br>'
        . '<h2>API</h2>'
        . '<a href="' . url()->route('api.me.get') . '">Logged in user info</a>'
        . '<br>'
        . '<a href="' . url()->route('api.news.list') . '">News list</a>'
        . '<br>'
        . '<a href="' . url()->route('api.news.show', ['id' => 1]) . '">News item (id 1)</a>';
})->name('public.index');


/**
 * Admin routes
 */
Router::group(['prefix' => 'admin'], function() {

    Router::add('GET', '/login', [\core\controllers\AdminController::class, 'loginView'])->name('admin.login.index');
    Router::add('POST', '/login', [\core\controllers\AdminController::class, 'loginPost'])->name('admin.login.post');
    Router::add('POST', '/logout', [\core\controllers\AdminController::class, 'logout'])->name('admin.logout');

    // routes only for logged-in users
    Router::group(['middleware' => \core\router\AuthMiddleware::class], function() {

        Router::add('GET', '/', [\core\controllers\AdminController::class, 'index'])->name('admin.index');

    });

    // if user is logged in, then load admin routes from modules
    if (auth()->isLoggedIn()) {
        // init active module routes
        foreach (app()->getModules() as $module => $moduleClass) {
            $callable = (new $moduleClass())->registerAdminRoutes();
            if (is_callable($callable)) {
                call_user_func($callable);
            }
        }
    }

});

/**
 * API routes
 */
Router::group(['prefix' => 'api'], function() {
    Router::add('GET', '/me', [\core\controllers\ApiController::class, 'getMe'])->name('api.me.get');

    // init active module API routes
    foreach (app()->getModules() as $module => $moduleClass) {
        $callable = (new $moduleClass())->registerApiRoutes();
        if (is_callable($callable)) {
            call_user_func($callable);
        }
    }
});


//echo '<pre>';
//var_dump(Router::$routes); exit();
