<?php

namespace Application\Controllers\Admin;

use Core\Controller;

class DashboardController extends Controller
{
    public function execute()
    {
        $this->twig->display('admin/dashboard.twig', []);
    }
}
