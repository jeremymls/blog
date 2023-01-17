<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\UserService;

/**
 * AdminUserController
 * 
 * Admin User Controller
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
     * index
     * 
     * Display the users admin list
     *
     * @param  mixed $nbr_show
     */
    public function index($nbr_show = 5)
    {
        $params = $this->userService->getAll();
        $params = $this->pagination->paginate($params, 'users', $nbr_show);
        $this->twig->display('admin/user/index.twig', $params);
    }

    /**
     * delete
     * 
     * Delete a user
     * 
     * @param  string $identifier
     */
    public function delete(string $identifier)
    {
        $this->userService->delete($identifier);
        echo 'done';
    }

    /**
     * role
     * 
     * Update a user role
     *
     * @param  string $identifier
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
