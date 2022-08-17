<?php

namespace Application\Controllers\Admin;

use Core\AdminController;

class DashboardController extends AdminController
{
    public function execute()
    {
        $this->twig->display('admin/dashboard.twig', []);
    }
}
