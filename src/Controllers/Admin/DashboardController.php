<?php

namespace Application\Controllers\Admin;

use Application\Controllers\Controller;
use Application\Lib\DatabaseConnection;
use Application\Repositories\CommentRepository;

class DashboardController extends Controller
{
    public function execute()
    {
        $this->twig->display('admin/dashboard.twig', []);
    }
}
