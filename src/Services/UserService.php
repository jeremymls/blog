<?php

namespace Application\Services;

use Application\Models\User;
use stdClass;

class UserService extends Service
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
    }

    public function show($id)
    {
        $params['user'] = $this->userRepository->findOne($id);
        return $params;
    }

    public function registerOrUpdateUser(string $action, array $input)
    {
        if ($input['password'] !== $input['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        $user = $this->validateForm($input,["email","password","first","last"]);
        $success = ($action === "register") ? 
            $this->userRepository->add($user) : 
            $this->userRepository->update($_SESSION['user']->id, $user);
        if (!$success) {
            throw new \Exception($action === "register" ? 'Impossible de créer l\'utilisateur !' : 'Impossible de modifier l\'utilisateur !');
        }
        $user = $this->userRepository->getUserByUsername($input['email']);
        $this->setUserSession($user);
        $target = ($action === "register") ? '/' : '/index.php?action=profil';
        return ['target' => $target];
    }

    public function login(array $input)
    {
        $user = $this->validateForm($input,["identifiant", "password"]);
        $user = $this->userRepository->getUserByUsername($input['identifiant']);
        if ($user->password !== $input['password']) {
            throw new \Exception("Mot de passe incorrect !");
        }
        $this->setUserSession($user);
    }

    public function setUserSession(User $user)
    {
        if (!isset($_SESSION)){
            session_start();
        } else {
            session_destroy();
            session_start();
        }
        $_SESSION['user'] = new stdClass();
        $_SESSION['user']->id = $user->identifier;
        $_SESSION['user']->username = $user->username;
        $_SESSION['user']->email = $user->email;
        $_SESSION['user']->first = $user->first;
        $_SESSION['user']->last = $user->last;
        $_SESSION['user']->role = $user->role;
        $_SESSION['user']->initials = $user->initials;
    }
}