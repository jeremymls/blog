<?php

namespace Application\Controllers\Security;

use Core\Controllers\Controller;
use Application\Services\UserService;
use Core\Services\TokenService;

/**
 * UserController
 * 
 * User Controller
 */
class UserController extends Controller
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
     * show
     * 
     * Display the user profil
     * if admin, display the user profil
     *
     * @param  mixed $identifier the user identifier
     */
    public function show($identifier = null)
    {
        $params = $this->userService->getData($identifier);
        $params = $this->pagination->paginate($params, 'comments', 5);
        $this->twig->display('security/profil.twig', $params);
    }

    /**
     * register
     * 
     * Register form
     */
    public function register()
    {
        if ($this->isPost()) {
            $this->userService->register($this->superglobals->getPost());
            $this->redirectWithTimeout('profil');
        } else {
            $this->twig->display('security/action.twig', ['action' => 'register',]);
        }
    }

    /**
     * update
     * 
     * Update form
     */
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

    /**
     * login
     * 
     * Login form
     */
    public function login($anchor = null)
    {
        if ($this->isPost()) {
            $this->userService->login($this->superglobals->getPost());
            $this->redirectWithTimeout(null, $anchor);
        } else {
            $this->twig->display('security/login.twig', []);
        }
    }

    /**
     * logout
     * 
     * Logout
     */
    public function logout()
    {
        $this->userService->logout();
        $this->superglobals->redirect('login');
    }

    /**
     * confirmation
     * 
     * Confirme the user email
     *
     * @param  string $token
     */
    public function confirmation(string $token)
    {
        $this->userService->confirmation($token);
        $this->redirectWithTimeout('profil');
    }

    /**
     * edit_mail
     * 
     * Edit the user email
     *
     * @param  mixed $identifier
     */
    public function edit_mail($identifier = null)
    {
        if ($this->isPost()) {
            $this->userService->edit_mail($this->superglobals->getPost());
            $this->redirectWithTimeout('profil');
        }
        $params = $this->userService->getData($identifier);
        $this->twig->display('security/edit_mail.twig', $params);
    }

    /**
     * edit_password
     * 
     * Edit the user password
     *
     * @param  mixed $identifier
     */
    public function edit_password($identifier = null)
    {
        if ($this->isPost()) {
            $this->userService->edit_password($this->superglobals->getPost());
            $this->redirectWithTimeout('profil');
        }
        $params = $this->userService->getData($identifier);
        $this->twig->display('security/edit_password.twig', $params);
    }

    /**
     * edit_picture
     * 
     * Edit the user picture
     */
    public function delete_picture()
    {
        $this->userService->delete_picture();
        echo 'done';
    }

    /**
     * forget_password
     * 
     * Forget password form
     */
    public function forget_password()
    {
        if ($this->isPost()) {
            $this->userService->forget_password($this->superglobals->getPost());
        $this->redirectWithTimeout('login');
        } else {
            $this->twig->display('security/forget_password.twig', []);
        }
    }

    /**
     * reset_password
     * 
     * Reset password form
     *
     * @param  string $token
     */
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

    /**
     * confirm_again
     * 
     * Send again the confirmation email
     */
    public function confirm_again()
    {
        $this->userService->confirm_again();
        $this->redirectWithTimeout('profil');
    }

    /**
     * checkUsername
     * 
     * Check if the username is available (AJAX)
     */
    public function checkUsername()
    {
        $this->userService->checkUsername($this->superglobals->getPost('username'));
    }

    /**
     * redirectWithTimeout
     * 
     * Redirect with a timeout
     * 
     * @param  string $pathName the path name to redirect to
     * @param  string $anchor the anchor to redirect to
     */
    public function redirectWithTimeout(string $pathName = null, $anchor = null)
    {
        $url = isset($pathName) ? $this->superglobals->getPath($pathName) : $this->session->getLastUrl($anchor);
        $this->twig->display('security/redirect.twig', ['target' => $url]);
    }
}
