<?php

namespace Core\Controllers;

use Core\Middleware\Security;

/**
 * AdminController
 * 
 * Make sure that the user is logged in before accessing any of the admin pages
 */
abstract class AdminController extends Controller
{    
    /**
     * __construct
     *
     * Launch the Security middleware
     */
    public function __construct()
    {
        parent::__construct();
        new Security();
    }
}
