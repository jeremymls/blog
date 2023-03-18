<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Controllers\Security
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Controllers\Security;

use Core\Controllers\Controller;
use Application\Services\UserService;
use Core\Services\TokenService;

/**
 * UserController
 *
 * User Controller
 *
 * @category Application
 * @package  Application\Controllers\Security
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
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
     * Show
     *
     * Display the user profil
     * if admin, display the user profil
     *
     * @param mixed $identifier the user identifier
     *
     * @return void
     */
    public function show($identifier = null)
    {
        $params = $this->userService->getData($identifier);
        $params = $this->pagination->paginate($params, 'comments', 5);
        $this->twig->display('security/profil.twig', $params);
    }

    /**
     * Register
     *
     * Register form
     *
     * @return void
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
     * Update
     *
     * Update form
     *
     * @return void
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
     * Login
     *
     * Login form
     *
     * @param string $anchor anchor
     *
     * @return void
     */
    public function login($anchor = null)
    {
        if ($this->userSession->isUser()) {
            $this->superglobals->redirect('home');
        }
        if ($this->isPost()) {
            $this->userService->login($this->superglobals->getPost());
            $this->redirectWithTimeout(null, $anchor);
        } else {
            $this->twig->display('security/login.twig', []);
        }
    }

    /**
     * Logout
     *
     * Logout the user
     *
     * @return void
     */
    public function logout()
    {
        $this->userService->logout();
        $this->superglobals->redirect('login');
    }

    /**
     * Confirmation
     *
     * Confirme the user email
     *
     * @param string $token the token
     *
     * @return void
     */
    public function confirmation(string $token)
    {
        $this->userService->confirmation($token);
        $this->redirectWithTimeout('profil');
    }

    /**
     * Edit Mail
     *
     * Edit the user email
     *
     * @param mixed $identifier the user identifier
     *
     * @return void
     */
    public function editMail($identifier = null)
    {
        if ($this->isPost()) {
            $this->userService->editMail($this->superglobals->getPost());
            $this->redirectWithTimeout('profil');
        }
        $params = $this->userService->getData($identifier);
        $this->twig->display('security/edit_mail.twig', $params);
    }

    /**
     * Edit Password
     *
     * Edit the user password
     *
     * @param mixed $identifier the user identifier
     *
     * @return void
     */
    public function editPassword($identifier = null)
    {
        if ($this->isPost()) {
            $this->userService->editPassword($this->superglobals->getPost());
            $this->redirectWithTimeout('profil');
        }
        $params = $this->userService->getData($identifier);
        $this->twig->display('security/edit_password.twig', $params);
    }

    /**
     * Delete Picture
     *
     * Delete the user picture in AJAX
     *
     * @return void
     */
    public function deletePicture()
    {
        $this->userService->deletePicture();
    }

    /**
     * Forget Password
     *
     * Forget password form
     *
     * @return void
     */
    public function forgetPassword()
    {
        if ($this->isPost()) {
            $this->userService->forgetPassword($this->superglobals->getPost());
            $this->redirectWithTimeout('login');
        } else {
            $this->twig->display('security/forget_password.twig', []);
        }
    }

    /**
     * Reset Password
     *
     * Reset password form
     *
     * @param string $token the token
     *
     * @return void
     */
    public function resetPassword($token)
    {
        $tokenService = new TokenService();
        $params = $tokenService->getUserByToken($token);
        if (isset($params['user'])) {
            if ($this->isPost()) {
                $this->userService->resetPassword(
                    $params['user'],
                    $this->superglobals->getPost()
                );
                $this->redirectWithTimeout('login');
            } else {
                $this->twig->display('security/reset_password.twig', $params);
            }
        }
    }

    /**
     * Confirm Again
     *
     * Send again the confirmation email
     *
     * @return void
     */
    public function confirmAgain()
    {
        $this->userService->confirmAgain();
        $this->redirectWithTimeout('profil');
    }

    /**
     * Check Username
     *
     * Check if the username is available (AJAX)
     *
     * @return void
     */
    public function checkUsername()
    {
        $this->userService->checkUsername($this->superglobals->getPost('username'));
    }

    /**
     * Redirect With Timeout
     *
     * Redirect with a timeout
     *
     * @param string $pathName the path name to redirect to
     * @param string $anchor   the anchor to redirect to
     *
     * @return void
     */
    public function redirectWithTimeout(string $pathName = null, $anchor = null)
    {
        $url = isset($pathName)
        ? $this->superglobals->getPath($pathName)
        : $this->session->getLastUrl($anchor);
        $this->twig->display('security/redirect.twig', ['target' => $url]);
    }
}
