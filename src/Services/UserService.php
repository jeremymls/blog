<?php

namespace Application\Services;

use Application\Repositories\UserRepository;
use Application\Lib\DatabaseConnection;

class UserService
{

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->userRepository->connection = new DatabaseConnection();
    }

    public function getUser(int $id)
    {
        $user = $this->userRepository->getUser($id);
        return [
            "user" => $user,
        ];
    }

    public function registerOrUpdateUser(string $action, array $input)
    {
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

        $success = ($action === "register") ?
        $this->userRepository->addUser($username, $password, $email, $first, $last) :
        $this->userRepository->updateUser($_SESSION['user']->id, $username, $password, $email, $first, $last);
        if (!$success) {
            throw new \Exception("Impossible d'ajouter un utilisateur !");
        }

        $this->setUserSession($email);
    }

    public function login(array $input)
    {
        $user = $input['user'];
        $password = $input['password'];

        $user = $this->userRepository->getUserByUsername($user);
        if ($user !== null && $user->password === $password) {
            $this->setUserSession($user->email);
        } else {
            throw new \Exception('Identifiants invalides !');
        }
    }

    public function setUserSession(string $username)
    {
        $user = $this->userRepository->getUserByUsername($username);
        if ($user !== null) {
            if (!isset($_SESSION)){
                session_start();
            } else {
                session_destroy();
                session_start();
            }
            $_SESSION['user']->id = $user->id;
            $_SESSION['user']->username = $user->username;
            $_SESSION['user']->email = $user->email;
            $_SESSION['user']->first = $user->first;
            $_SESSION['user']->last = $user->last;
            $_SESSION['user']->role = $user->role;
        } else {
            throw new \Exception("Impossible de récupérer l'utilisateur !");
        }
    }

}