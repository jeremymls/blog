<?php

namespace Application\Services;

use Core\Service;
use Application\Models\User;
use Core\Services\TokenService;
use stdClass;

class UserService extends Service
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
        $this->tokenService = new TokenService();
    }

    public function show($id)
    {
        if (!$id) {
            if (isset($_SESSION['user'])) {
                $id = $_SESSION['user']->id;
            } else {
                header('Location: /login?redirect='.$_REQUEST['url']);
            }
        }
        $params['user'] = $this->userRepository->findOne($id);
        $params['user']->withExpirationToken();
        $params['comments'] = $this->commentRepository->getCommentsByUser($id);
        $params['commentsCount'] = count($params['comments']);
        $params['commentsPendingCount'] = count(array_filter($params['comments'], function($obj){return $obj->moderate == 0;}));
        $params= $this->pagination($params, 'comments', 5);
        return $params;
    }

    public function getUsers()
    {
        $params['users'] = $this->userRepository->findAll();
        $params= $this->pagination($params, 'users', 5);
        return $params;
    }

    public function register(array $input, $userId)
    {
        if ($input['password'] !== $input['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        $user = $this->validateForm($input,["email","password","first","last"]);
        $success = $this->userRepository->add($user);
        if (!$success) {
            throw new \Exception("Impossible de créer l'utilisateur ! <br>L'adresse e-mail est peut-être déjà utilisée");
        }
        $this->flash->success(
            'Utilisateur créé',
            'L\'utilisateur '. $input['email'] .' a bien été créé'
        );
        $user = $this->userRepository->getUserByUsername($input['email']);
        $token = $this->tokenService->createToken($user->identifier);
        $this->sendConfirmationEmail($input['email'], $input['first'] , $token);
        if (isset($_SESSION['user']) && $_SESSION['user']->role === "admin"){
            header("Location: /admin/users");
        } else {
            $this->setUserSession($user);
            return ['target' => "/profil"];
        }
    }

    public function updateUser(array $input, $userId = null)
    {
        if ($input['password'] !== $input['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        $user = $this->validateForm($input,["email","password","first","last"]);
        $success = $this->userRepository->update($userId?$userId : $_SESSION['user']->id, $user);
        if (!$success) {
            throw new \Exception("Impossible de modifier l'utilisateur !");
        }
        $this->flash->success(
            'Utilisateur modifié',
            'L\'utilisateur '. $input['email'] .' a bien été modifié'
        );
        if (isset($_SESSION['user']) && $_SESSION['user']->role === "admin" && isset($userId)){
            header("Location: /admin/users");
        } else {
            $user = $this->userRepository->getUserByUsername($input['email']);
            $this->setUserSession($user);
            return ['target' => "/profil"];
        }
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

    public function logout()
    {
        session_destroy();
        $this->flash->danger(
            'Déconnexion',
            'Vous êtes déconnecté'
        ); 
    }

    public function delete($identifier)
    {
        $success = $this->userRepository->delete($identifier);
        if (!$success) {
            throw new \Exception("Impossible de supprimer l'utilisateur !");
        } 
        $this->flash->success(
            'Utilisateur supprimé',
            'L\'utilisateur '. $identifier .' a bien été supprimé'
        ); 
    }

    public function confirmation($token)
    {
        $user = $this->tokenRepository->getUserByToken($token);
        if ($user->validated_email == "" || $user->validated_email == null) {
            $this->userRepository->update($user->identifier, ['validated_email' => 1]);
            $user->validated_email = "1";
            $this->flash->success(
                'Confirmation de compte',
                'Votre email est validé !'
            );
        } else {
            $this->flash->warning(
                'Confirmation de compte',
                'Votre email est déjà validé !'
            );
        }
        $this->setUserSession($user);
    }

    public function edit_mail($input)
    {
        if ($input['email'] !== $input['retape']) {
            throw new \Exception('Les adresses ne correspondent pas.');
        }
        $success = $this->userRepository->update(
            $_SESSION['user']->id,
            [
                'email' => $input['email'],
                'validated_email' => 0
            ]);
        if (!$success) {
            throw new \Exception("Impossible de modifier l'e-mail <br>Cette adresse est peut-être déjà utilisée");
        }
        $this->flash->success(
            'E-mail modifiée',
            'L\'e-mail a bien été modifiée'
        );
        $_SESSION['user']->validated_email = 0;
        $user = $this->userRepository->getUserByUsername($input['email']);
        $token = $this->tokenService->createToken($user->identifier);
        $this->sendConfirmationEmail($input['email'], $user->first , $token);
    }

    public function edit_password(array $input)
    {
        $user = $this->userRepository->findOne($_SESSION['user']->id);        
        if ($input['currentPassword'] !== $user->password) {
            throw new \Exception('Vous n\'avez pas tapé le bon mot de passe actuel.');
        }
        if ($input['password'] !== $input['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        $success = $this->userRepository->update(
            $_SESSION['user']->id,
            [
                'password' => $input['password']
            ]);
        if (!$success) {
            throw new \Exception("Impossible de modifier le mot de passe");
        }
        $this->flash->success(
            'Mot de passe modifié',
            'Le mot de passe a bien été modifié'
        );
    }

    public function delete_picture()
    {
        $success = $this->userRepository->update($_SESSION['user']->id,['picture' => null]);
        if (!$success) {
            throw new \Exception("Impossible de supprimer la photo de profil");
        }
        $this->flash->success(
            'Photo de profil supprimée',
            'La photo de profil a bien été supprimée'
        );
    }

    public function setUserSession(User $user)
    {
        if (!isset($_SESSION)){
            session_start();
        } else {
            session_unset();
        }
        if (!isset($_SESSION)){
            session_start();
        }
        $_SESSION['user'] = new stdClass();
        $_SESSION['user']->id = $user->identifier;
        $_SESSION['user']->username = $user->username;
        $_SESSION['user']->email = $user->email;
        $_SESSION['user']->first = $user->first;
        $_SESSION['user']->last = $user->last;
        $_SESSION['user']->role = $user->role;
        $_SESSION['user']->validated_email = $user->validated_email;
        $_SESSION['user']->initials = $user->initials;
    }
}