<?php

namespace Application\Controllers\Security;

use Application\Controllers\Controller;
use Application\Lib\DatabaseConnection;
use Application\Repositories\CommentRepository;
use Application\Repositories\UserRepository;

class User extends Controller
{
    public function action(string $action, ?array $input)
    {
        // It handles the form submission when there is an input.
        if ($input !== null) {

            $username = null;
            $password = null;
            $email = null;
            $first = null;
            $last = null;

            if (!empty($input['first']) && !empty($input['last']) && !empty($input['email']) && !empty($input['password'])) {

                $username = !empty($input['username']) ? $input['username'] : null;
                $password = $input['password'];
                $email = $input['email'];
                $first = $input['first'];
                $last = $input['last'];

            } else {
                throw new \Exception('Les données du formulaire sont invalides.');
            }

            $userRepository = new UserRepository();
            $userRepository->connection = new DatabaseConnection();
            $success = ($action === "register") ? $userRepository->addUser($username, $password, $email, $first, $last): $userRepository->updateUser($_SESSION['user']->id, $username, $password, $email, $first, $last);
            if (!$success) {
                throw new \Exception("Impossible d'ajouter un utilisateur !");
            } else {
                if(! isset($_SESSION)){
                    session_start();
                } else {
                    session_destroy();
                    session_start();
                }
                $user = $userRepository->getUserByUsername($email);
                $_SESSION['user']->id = $user->id;
                $_SESSION['user']->username = $user->username;
                $_SESSION['user']->email = $user->email;
                $_SESSION['user']->first = $user->first;
                $_SESSION['user']->last = $user->last;
                $_SESSION['user']->role = 'user';

                $this->twig->display('security/redirect.twig', [
                    'target' => ($action === "register")?'/': '/index.php?action=profil',
                ]);
            }
        } else {

            if ($action === 'edit') {
                // Otherwise, it displays the form.
                $userRepository = new UserRepository();
                $userRepository->connection = new DatabaseConnection();
                $user = $userRepository->getUser($_SESSION['user']->id);
                $this->twig->display('security/action.twig', [
                    'user' => $user,
                    'action' => $action,
                ]);
            } elseif ($action === "register" ) {
                $this->twig->display('security/action.twig', [
                    'action' => $action,
                ]);
            }
        }
    }

    public function show()
    {
        $userRepository = new UserRepository();
        $userRepository->connection = new DatabaseConnection();
        $user = $userRepository->getUser($_SESSION['user']->id);
        $this->twig->display('security/profil.twig', [
            'user' => $user,
        ]);
    }

    public function login(?array $input)
    {
        if ($input !== null) {
            $user = $input['user'];
            $password = $input['password'];
            $userRepository = new UserRepository();
            $userRepository->connection = new DatabaseConnection();
            $user = $userRepository->getUserByUsername($user);
            if ($user !== null && $user->password === $password) {
                if(! isset($_SESSION)){
                    session_start();
                }
                // var_dump($user);
                // die();
                $_SESSION['user']->id = $user->id;
                $_SESSION['user']->username = $user->username;
                $_SESSION['user']->email = $user->email;
                $_SESSION['user']->first = $user->first;
                $_SESSION['user']->last = $user->last;
                $_SESSION['user']->role = $user->role;

                $this->twig->display('security/redirect.twig', [
                    'target' => '/',
                ]);
            } else {
                throw new \Exception('Identifiant ou mot de passe incorrect.');
            }
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