<?php

namespace Core\Controllers;

class DocController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->twig->display('admin/doc/index.twig');
    }
}
