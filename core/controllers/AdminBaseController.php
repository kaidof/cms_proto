<?php

namespace core\controllers;

use core\Template;

class AdminBaseController
{
    public function __construct()
    {
        // set layout for admin pages
        Template::layout('admin/layout', [ 'pageTitle' => 'Admin' ]);

        // add admin css to layout for all admin controller actions
        assets()->add(url()->getFullBaseUrl() . '/core/assets/css/admin.css', 'css');
    }
}
