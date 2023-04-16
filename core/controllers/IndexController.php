<?php

namespace core\controllers;

class IndexController
{
    public function index()
    {
        return view('layout', [
            'pageTitle' => 'Hello World',
            'content' => 'This is the content',
        ]);
    }
}
