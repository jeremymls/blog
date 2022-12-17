<?php

namespace Application\Controllers\Security;

use Core\Controllers\Controller;
use Application\Services\UserService;
use Core\Services\TokenService;

class UserController extends Controller
{
    private $userService;

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

    public function register()
    {
        if ($this->isPost()) {
            $this->userService->register($this->superglobals->getPost());
            $this->redirectWithTimeout('profil');
        } else {
            $this->twig->display('security/action.twig', ['action' => 'register',]);
        }
    }

    public function update()
    {
        $userId = $this->superglobals->getGet('userId');
        if ($this->isPost()) {
            $this->userService->updateUser($this->superglobals->getPost(), $userId);
            $this->redirectWithTimeout('profil');
        } else {
            $params = $this->userService->getData($userId);
            $this->twig->display('security/action.twig', $params);
        }
    }

    public function login($anchor = null)
    {
        if ($this->isPost()) {
            $this->userService->login($this->superglobals->getPost());
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
            $this->userService->edit_mail($this->superglobals->getPost());
            $this->redirectWithTimeout('profil');
        }
        $params = $this->userService->getData($identifier);
        $this->twig->display('security/edit_mail.twig', $params);
    }

    public function edit_password($identifier = null)
    {
        if ($this->isPost()) {
            $this->userService->edit_password($this->superglobals->getPost());
            $this->redirectWithTimeout('profil');
        }
        $params = $this->userService->getData($identifier);
        $this->twig->display('security/edit_password.twig', $params);
    }

    public function delete_picture()
    {
        $this->userService->delete_picture();
        $this->superglobals->redirect('profil');
    }

    public function forget_password()
    {
        if ($this->isPost()) {
            $this->userService->forget_password($this->superglobals->getPost());
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
                $this->userService->reset_password($params['user'], $this->superglobals->getPost());
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
        $this->userService->checkUsername($this->superglobals->getPost('username'));
    }

    public function redirectWithTimeout(string $pathName = null, $anchor = null)
    {
        $url = isset($pathName) ? $this->superglobals->getPath($pathName) : $this->session->getLastUrl($anchor);
        $this->twig->display('security/redirect.twig', ['target' => $url]);
    }
}
