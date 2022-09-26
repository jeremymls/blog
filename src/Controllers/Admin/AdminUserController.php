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
        $this->twig->display('admin/user/index.twig', $params);
    }

    public function delete(string $identifier)
    {
        $this->userService->delete($identifier);
        header('Location: /admin/users');
    }

}
