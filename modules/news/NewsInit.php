<?php

namespace modules\news;

use core\router\Router;
use modules\news\controllers\NewsApiController;
use modules\news\controllers\NewsController;

class NewsInit
{
    public function init()
    {
        $this->initHooks();
    }

    public function initHooks()
    {
        hook()->add('admin_module_menu', function ($params) {

            $params[] = [
                'title' => 'News',
                'url' => url()->route('admin.news.index'),
                'url_name' => 'admin.news.index',
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
            Router::group(['prefix' => '/news'], function() {
                Router::add('GET', '/', [NewsController::class, 'adminIndex'])->name('admin.news.index');
                Router::add('GET', '/{id}', [NewsController::class, 'show'])->name('admin.news.show');
                Router::add('DELETE', '/{id}', [NewsController::class, 'delete'])->name('admin.news.delete');
            });
        };
    }

    public function registerApiRoutes()
    {
        return function () {
            // var_dump('registerApiRoutes >>>>>>');
            Router::add('GET', '/news', [NewsApiController::class, 'newsList'])->name('api.news.list');
            Router::add('GET', '/news/{id}', [NewsApiController::class, 'newsById'])->name('api.news.show');
        };
    }
}
