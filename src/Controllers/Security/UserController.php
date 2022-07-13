<?php

namespace Application\Controllers\Security;

use Application\Controllers\Controller;
use Application\Services\UserService;
class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    public function action(string $action, ?array $input)
    {
        if ($input !== null) {
            $this->userService->registerOrUpdateUser($action, $input);
            $target = ($action === "register") ? '/' : '/index.php?action=profil';
            $this->twig->display('security/redirect.twig', [
                'target' => $target
            ]);
        } else {

            if ($action === 'edit') {
                $params = $this->userService->getUser($_SESSION['user']->id);
                $params["action"] = $action;
                $this->twig->display('security/action.twig', $params);
            } elseif ($action === "register" ) {
                $this->twig->display('security/action.twig', ['action' => $action,]);
            }
        }
    }

    public function show()
    {
        $params = $this->userService->getUser($_SESSION['user']->id);
        $this->twig->display('security/profil.twig', $params);
    }

    public function login(?array $input)
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