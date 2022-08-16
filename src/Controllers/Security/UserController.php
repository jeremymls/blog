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
        $params = $this->userService->show($identifier);
        $this->twig->display('security/profil.twig', $params);
    }

    public function action(string $action)
    {
        $userId = isset($_GET["userId"]) ? $_GET["userId"] : null;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $params = $this->userService->registerOrUpdateUser($action, $_POST, $userId);
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

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_GET['redirect']) && $_GET['redirect'] !== '') {
                $target = $_GET['redirect'];
            } else {
                $target = '/';
            }
            $this->userService->login($_POST);
            $this->twig->display('security/redirect.twig', ['target' => $target]);
        } else {
            $this->twig->display('security/login.twig', []);
        }
    }

    public function logout()
    {
        $this->userService->logout();
        header('Location: /login');
    }

    public function confirmation(string $token)
    {
        $this->userService->confirmation($token);
        $this->twig->display('security/redirect.twig', ['target' => '/profil']);
    }

    public function edit_mail($identifier = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $this->userService->edit_mail($_POST);
        $this->twig->display('security/redirect.twig', ['target' => '/profil']);
        }
        $params = $this->userService->show($identifier);
        $this->twig->display('security/edit_mail.twig', $params);
    }

    public function edit_password($identifier = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->userService->edit_password($_POST);
            $this->twig->display('security/redirect.twig', ['target' => '/profil']);
        }
        $params = $this->userService->show($identifier);
        $this->twig->display('security/edit_password.twig', $params);
    }
}