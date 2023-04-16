<?php

namespace modules\news2;

use core\router\Router;
use modules\news2\controllers\NewsApiController;
use modules\news2\controllers\NewsController;

class NewsInit
{
    public function init()
    {
        $this->initHooks();
    }

    public function initHooks()
    {
        // var_dump(__METHOD__);

        // 'admin.leftMenu'
        hook()->add('admin_module_menu', function ($params) {

            $params[] = [
                'title' => 'News copy2',
                // 'url' => url()->route('admin.news2.index'),
                'url_name' => 'admin.news2.index',
                'icon' => 'fa fa-newspaper-o',
                'order' => 10,
            ];

            return $params;
        });

    }

    /**
     * @return \Closure
     */
    public function registerAdminRoutes()
    {
        return function () {
            // var_dump('registerAdminRoutes >>>>>>');

            Router::group(['prefix' => '/news2'], function() {
                Router::add('GET', '/', [NewsController::class, 'adminIndex'])->name('admin.news2.index');
                Router::add('GET', '/{id}', [NewsController::class, 'show'])->name('admin.news2.show');
                Router::add('DELETE', '/{id}', [NewsController::class, 'delete'])->name('admin.news2.delete');
            });
        };
    }

    public function registerApiRoutes()
    {
        return function () {
            // var_dump('registerApiRoutes >>>>>>');
            Router::add('GET', '/news2', [NewsApiController::class, 'newsList']);
            Router::add('GET', '/news2/{id}', [NewsApiController::class, 'newsById']);
        };
    }
}
