<?php

namespace Application\Controllers\Admin;

use Core\Controller;
use Application\Services\UserService;

class AdminUserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    public function index()
    {
        $params = $this->userService->getUsers();
        $this->twig->display('admin/user/index.twig', $params);
    }

    public function delete(string $identifier)
    {
        $this->userService->delete($identifier);
        header('Location: index.php?action=userAdmin&flush=success');
    }

}
