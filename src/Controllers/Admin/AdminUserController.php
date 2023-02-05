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
use Application\Services\UserService;

/**
 * AdminUserController
 *
 * Admin User Controller
 *
 * @category Application
 * @package  Application\Controllers\Admin
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class AdminUserController extends AdminController
{
    private $userService;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    /**
     * Index
     *
     * Display the users admin list
     *
     * @param mixed $nbr_show the number of users to show
     *
     * @return void
     */
    public function index($nbr_show = 5)
    {
        $params = $this->userService->getAll();
        $params = $this->pagination->paginate($params, 'users', $nbr_show);
        $this->twig->display('admin/user/index.twig', $params);
    }

    /**
     * Delete
     *
     * Delete a user
     *
     * @param string $identifier the user identifier
     *
     * @return void
     */
    public function delete(string $identifier)
    {
        $this->userService->delete($identifier);
        echo 'done';
    }

    /**
     * Role
     *
     * Update a user role
     *
     * @param string $identifier the user identifier
     *
     * @return void
     */
    public function role(string $identifier)
    {
        if ($this->isPost()) {
            $this->userService->update(
                $identifier,
                $this->superglobals->getPost()
            );
            $this->superglobals->redirect('admin:users');
        }
        $params = $this->userService->get($identifier);
        $this->twig->display('admin/user/role.twig', $params);
    }
}
