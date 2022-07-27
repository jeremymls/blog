<?php

namespace Application\Controllers\Security;

use Core\Controller;
use Application\Services\UserService;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    public function show($identifier = null)
    {
        $params = $this->userService->show($identifier? $identifier : $_SESSION['user']->id);
        $this->twig->display('security/profil.twig', $params);
    }

    public function action(string $action, array $input = null)
    {
        $userId = isset($_GET["userId"]) ? $_GET["userId"] : null;
        if ($input !== null) {
            $params = $this->userService->registerOrUpdateUser($action, $input, $userId);
            $this->twig->display('security/redirect.twig', $params);
        } else {
            if ($action === 'edit') {
                $params = $this->userService->show($userId ? $userId : $_SESSION['user']->id);
                $params["action"] = $action;
                $this->twig->display('security/action.twig', $params);
            } else {
                $this->twig->display('security/action.twig', ['action' => $action,]);
            }
        }
    }

    public function login(array $input = null)
    {
        if ($input !== null) {
            $this->userService->login($input);
            $this->twig->display('security/redirect.twig', ['target' => '/']);
        } else {
            $this->twig->display('security/login.twig', []);
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?action=login');
    }

}