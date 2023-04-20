<?php

namespace modules\news2\controllers;

use core\datatable\Datatable;
use core\Template;
use modules\news2\models\NewsModel;

class NewsController
{
    public function __construct()
    {
        // add admin css
        assets()->add(url()->getFullBaseUrl() . '/core/assets/css/admin.css', 'css');
    }

    public function index()
    {
        // Router::add('GET', '/main', [\App\controllers\IndexController::class, 'index']);

        return 'News2 index';
    }

    public function show($id)
    {
        return 'News2 show ' . $id;
    }

    /**
     * Delete news item
     *
     * @param $id
     * @return \core\router\Response
     */
    public function delete($id)
    {
        // NewsModel::find($id)->delete();

        return response()->setStatusCode(204);
        // return redirect()->route('admin.news.index');
    }

    public function adminIndex()
    {

        $datatable = new Datatable([
            'data_table' => 'news',
            // 'data_sql' => 'SELECT SQL_CALC_FOUND_ROWS * FROM news',
            'default_order_id' => 'id',
            'default_order_dir' => 'desc',
            'items_per_page' => 10,
        ]);

        $datatable
            ->addColumn('id', 'id', 'ID', 'text', [
                'width' => '10%',
                'sortable' => true,
                'searchable' => true,
            ])
            ->addColumn('title', 'title', 'Title', 'text', [
                'sortable' => true,
            ])
            ->addColumn('text', 'content', 'Article', 'text', [
                'sortable' => true,
            ])
            ->addColumn('is_active', 'is_active', 'Active', Datatable::TYPE_BOOLEAN, [
                'width' => '10%',
                'sortable' => true,
                'searchable' => true,
            ])
            ->addColumn('action_edit', null, 'Edit', Datatable::TYPE_CUSTOM, [
                'cellContent' => function ($val, $row) {
                    return '<a href="' . url()->route('admin.news2.show', ['id' => $row['id']]) . '" class="edit2">Edit</a>';
                },
            ])
            ->addColumn('action_delete', null, 'Delete', Datatable::TYPE_CUSTOM, [
                'cellContent' => function ($val, $row) {
                    return '<a href="' . url()->route('admin.news2.show', ['id' => $row['id']]) . '" class="delete">Delete</a>';

                    // return from with DELETE method
                    /*
                    return '<form action="' . url()->route('admin.news.delete', ['id' => $row['id']]) . '" method="post">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="csrf_token">
                        <button type="submit" class="delete">Delete</button>
                    </form>';
                    */
                },
            ])
        ;

        return view('news2::news_block', [
            'pageTitle' => 'News2',
            'title' => 'Uuudis',
            'content' => 'Uudise sisu!',
            'leftMenu' => $this->getLeftMenu(),
            'datatable' => $datatable->getHtml(),
        ]);
    }

    public function getLeftMenu()
    {
        // return get_admin_module_menu();

        return [
            [
                'title' => '< Main menu',
                //'url' => url()->route('admin.index'),
                'url_name' => 'admin.index',
                'icon' => 'fa fa-newspaper-o',
                'order' => 1,
            ],
            [
                'title' => 'News2 list',
                //'url' => url()->route('admin.news2.index'),
                'url_name' => 'admin.news2.index',
                'icon' => 'fa fa-newspaper-o',
                'order' => 10,
            ],
        ];
    }
}
