<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;

/**
 * DashboardController
 * 
 * Admin Dashboard Controller
 */
class DashboardController extends AdminController
{
    /**
     * execute
     * 
     * Display the dashboard
     */
    public function execute()
    {
        $this->twig->display('admin/dashboard.twig', []);
    }
}
