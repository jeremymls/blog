<?php

namespace Core\Controllers;

/**
 * DocController
 * 
 * Display the documentation
 */
class DocController extends AdminController
{    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * index
     * 
     * It displays the index.twig file in the admin/doc folder.
     */
    public function index()
    {
        $this->twig->display('admin/doc/index.twig');
    }
}
