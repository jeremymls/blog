<?php

namespace Application\Controllers\Security;

use Core\Controllers\Controller;
use Application\Services\UserService;
use Core\Services\TokenService;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    public function show($identifier = null)
    {
        $params = $this->userService->getData($identifier);
        $params = $this->pagination->paginate($params, 'comments', 5);
        $this->twig->display('security/profil.twig', $params);
    }

    public function register($userId = null)
    {
        if ($this->isPost()) {
            $this->userService->register($_POST, $userId);
            $this->redirectWithTimeout('profil');
        } else {
            $this->twig->display('security/action.twig', ['action' => 'register',]);
        }
    }

    public function update()
    {
        $userId = isset($_GET["userId"]) ? $_GET["userId"] : null;
        if ($this->isPost()) {
            $this->userService->updateUser($_POST, $userId);
            $this->redirectWithTimeout('profil');
        } else {
            $params = $this->userService->getData($userId);
            $this->twig->display('security/action.twig', $params);
        }
    }

    public function login($anchor = null)
    {
        if ($this->isPost()) {
            $this->userService->login($_POST);
            $this->redirectWithTimeout(null, $anchor);
        } else {
            $this->twig->display('security/login.twig', []);
        }
    }

    public function logout()
    {
        $this->userService->logout();
        $this->superglobals->redirect('login');
    }

    public function confirmation(string $token)
    {
        $this->userService->confirmation($token);
        $this->redirectWithTimeout('profil');
    }

    public function edit_mail($identifier = null)
    {
        if ($this->isPost()) {
            $this->userService->edit_mail($_POST);
            $this->redirectWithTimeout('profil');
        }
        $params = $this->userService->getData($identifier);
        $this->twig->display('security/edit_mail.twig', $params);
    }

    public function edit_password($identifier = null)
    {
        if ($this->isPost()) {
            $this->userService->edit_password($_POST);
            $this->redirectWithTimeout('profil');
        }
        $params = $this->userService->getData($identifier);
        $this->twig->display('security/edit_password.twig', $params);
    }

    public function delete_picture($identifier = null)
    {
        $this->userService->delete_picture($identifier);
        $params = $this->userService->getData($identifier);
        $this->twig->display('security/profil.twig', $params);
    }

    public function forget_password()
    {
        if ($this->isPost()) {
            $this->userService->forget_password($_POST);
        $this->redirectWithTimeout('login');
        } else {
            $this->twig->display('security/forget_password.twig', []);
        }
    }

    public function reset_password($token)
    {
        $tokenService = new TokenService();
        $params = $tokenService->getUserByToken($token);
        if (isset($params['user'])) {
            if ($this->isPost()) {
                $this->userService->reset_password($params['user'], $_POST);
                $this->redirectWithTimeout('login');
            } else {
                $this->twig->display('security/reset_password.twig', $params);
            }
        }
    }

    public function confirm_again()
    {
        $this->userService->confirm_again();
        $this->redirectWithTimeout('profil');
    }

    // AJAX
    public function checkUsername()
    {
        $this->userService->checkUsername($_POST['username']);
    }

    public function redirectWithTimeout(string $pathName = null, $anchor = null)
    {
        $url = isset($pathName) ? $this->superglobals->getPath($pathName) : $this->session->getLastUrl($anchor);
        $this->twig->display('security/redirect.twig', ['target' => $url]);
    }
}
