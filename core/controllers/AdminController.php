<?php

namespace core\controllers;

class AdminController extends AdminBaseController
{

    public function index()
    {
        view_var('global1', 'Global_1');

        return view('admin/index', [
            'pageTitle' => 'Admin INDEX',
            'content' => 'This is the content',
            'leftMenu' => $this->getLeftMenu(),
        ]);
    }

    public function loginView()
    {
        // if user is logged in, redirect to admin index page
        if (auth()->isLoggedIn()) {
            return redirect()->route('admin.index');
        }

        return view('admin/login', [
            'pageTitle' => 'Admin Login',
            'content' => 'This is the content for login page',
        ]);
    }

    public function loginPost()
    {
        $user = request()->post('username');
        $pass = request()->post('password');

        if (auth()->login($user, $pass)) {
            return redirect()->route('admin.index');
        }

        return redirect()->route('admin.login.index');
    }

    public function logout()
    {
        // destroy session
        auth()->logout();

        return redirect()->route('admin.login.index');
    }

    public function getLeftMenu()
    {
        // use globally defined function
        return get_admin_module_menu();


        // use hooks functionality to get left menu items from modules
        /*
        $menuList = [];

        $menuList = (array)hook()->run('admin_module_menu', $menuList);

        // sort by order property
        usort($menuList, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return $menuList;
        */


        /*
        return [
            [
                'title' => 'Dashboard',
                'url' => url()->route('admin.index'),
                'icon' => 'fa fa-dashboard',
            ],
            [
                'title' => 'Users',
                'url' => url()->route('admin.users.index'),
                'icon' => 'fa fa-users',
            ],
            [
                'title' => 'News',
                'url' => url()->route('admin.news.index'),
                'icon' => 'fa fa-newspaper-o',
            ],
            [
                'title' => 'Settings',
                'url' => url()->route('admin.settings.index'),
                'icon' => 'fa fa-cogs',
            ],
        ];
        */
    }
}
