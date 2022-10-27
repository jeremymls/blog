<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\UserService;

class AdminUserController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    public function index()
    {
        $params = $this->userService->getAll();
        $params = $this->pagination->paginate($params, 'users', 10);
        $this->twig->display('admin/user/index.twig', $params);
    }

    public function delete(string $identifier)
    {
        $this->userService->delete($identifier);
        $this->superglobals->redirect('admin:users');
    }

    public function role(string $identifier)
    {
        if ($this->isPost()) {
            $this->userService->update(
                $identifier, 
                $_POST
            );
        $this->superglobals->redirect('admin:users');
        }
        $params = $this->userService->get($identifier);
        $this->twig->display('admin/user/role.twig', $params);
    }
}
