<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Controllers\Admin
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;

/**
 * DashboardController
 *
 * Admin Dashboard Controller
 *
 * @category Application
 * @package  Application\Controllers\Admin
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class DashboardController extends AdminController
{
    /**
     * Execute
     *
     * Display the dashboard
     *
     * @return void
     */
    public function execute()
    {
        $this->twig->display('admin/dashboard.twig', []);
    }
}
