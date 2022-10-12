<?php

namespace Application\Services;

use Application\Models\User;
use Application\Repositories\CommentRepository;
use Core\Services\MailService;
use Core\Services\ConfigService;
use Core\Services\EntityService;
use Core\Services\TokenService;
use stdClass;

class UserService extends EntityService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
        $this->tokenService = new TokenService();
        $this->mailService = new MailService();
        $this->configService = new ConfigService();
    }

    public function register(array $input)
    {
        if ($input['password'] !== $input['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        $this->add($input, ["role" => "user"]);
        $params = $this->getBy('email = ?', [$input['email']]);
        $user = $params['user'];
        $token = $this->tokenService->createToken($user->identifier);
        $this->sendConfirmationEmail($input['email'], $input['first'], $token);
        if (isset($_SESSION['user']) && $_SESSION['user']->role === "admin") {
            header("Location: /admin/users");
        } else {
            $this->setUserSession($user);
            return ['target' => "/profil"];
        }
    }

    public function show($id)
    {
        if (isset($id) && isset($_SESSION['user']) && $_SESSION['user']->role == 'admin') {
            $id = (int) $id;
        } else {
            if (isset($_SESSION['user'])) {
                $id = $_SESSION['user']->id;
            } else {
                header('Location: /login?redirect='.$_REQUEST['url']);
            }
        }
        $params = $this->get($id);
        $params['user']->withExpirationToken();
        $commentRepository = new CommentRepository();
        $params['comments'] = $commentRepository->findAll("WHERE author = ?", [$id]);
        $params['commentsCount'] = count($params['comments']);
        $params['commentsPendingCount'] = count(
            array_filter(
                $params['comments'], function ($obj) {
                    return $obj->moderate == 0;
                }
            )
        );
        return $params;
    }

    public function updateUser(array $input, $userId = null)
    {
        $id = $userId ?? $_SESSION['user']->id;
        $this->update(
            $id, 
            $input,
            'L\'utilisateur '. $input['email'] .' a bien été modifié'
        );
        if (isset($_SESSION['user']) && $_SESSION['user']->role === "admin" && isset($userId)) {
            header("Location: /admin/users");
        } else {
            $user = $this->repository->getUserByUsername($input['email']);
            $this->setUserSession($user);
            return ['target' => "/profil"];
        }
    }

    public function login(array $input)
    {
        ['identifiant' => $identifiant, 'password' => $password] = $input;
        $user = $this->repository->getUserByUsername($identifiant);
        if (!$user->comparePassword($user->password, $password)) {
            throw new \Exception("Mot de passe incorrect !");
        }
        $this->setUserSession($user);
        $this->flashServices->success(
            'Connexion réussie',
            'Vous êtes connecté(e) !'
        );
    }

    public function logout()
    {
        session_destroy();
        $this->flashServices->danger(
            'Déconnexion',
            'Vous êtes déconnecté(e) !'
        ); 
    }

    public function confirmation($token)
    {
        $params = $this->tokenService->getUserByToken($token);
        $user = $params['user'];
        if ($user->validated_email == "" || $user->validated_email == null) {
            $this->update($user->identifier, ['validated_email' => 1]);
            $user->validated_email = "1";
            $this->setUserSession($user);
            $this->flashServices->success(
                'Confirmation de compte',
                'Votre email est validé !'
            );
        } else {
            $this->flashServices->warning(
                'Confirmation de compte',
                'Votre email est déjà validé !'
            );
        }
    }

    // todo: revoir
    public function edit_mail($input)
    {
        if ($input['email'] !== $input['retape']) {
            throw new \Exception('Les adresses ne correspondent pas.');
        }
        $success = $this->repository->update(
            $_SESSION['user']->id,
            [
                'email' => $input['email'],
                'validated_email' => 0
            ]
        );
        if (!$success) {
            throw new \Exception("Impossible de modifier l'e-mail <br>Cette adresse est peut-être déjà utilisée");
        }
        $this->flashServices->success(
            'E-mail modifiée',
            'L\'e-mail a bien été modifiée'
        );
        $_SESSION['user']->validated_email = 0;
        $user = $this->repository->getUserByUsername($input['email']);
        $token = $this->tokenService->createToken($user->identifier);
        $this->sendConfirmationEmail($input['email'], $user->first, $token);
    }

    public function edit_password(array $input)
    {
        $user = $this->repository->findOne($_SESSION['user']->id);        
        if ($input['password'] !== $input['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        if (!$user->comparePassword($user->password, $input['currentPassword'])) {
            throw new \Exception('Ce n\'est le bon mot de passe actuel.');
        }
        $user->setPassword($input['password']);
        $success = $this->repository->update(
            $_SESSION['user']->id,
            ['password' => $user->password,]
        );
        if (!$success) {
            throw new \Exception("Impossible de modifier le mot de passe");
        }
        $this->flashServices->success(
            'Mot de passe modifié',
            'Le mot de passe a bien été modifié'
        );
    }

    public function delete_picture()
    {
        $success = $this->repository->update($_SESSION['user']->id, ['picture' => null]);
        if (!$success) {
            throw new \Exception("Impossible de supprimer la photo de profil");
        }
        $this->flashServices->success(
            'Photo de profil supprimée',
            'La photo de profil a bien été supprimée'
        );
        $_SESSION['user']->picture = null;
    }

    public function setUserSession(User $user)
    {
        if (!isset($_SESSION)) {
            session_start();
        } else {
            session_unset();
        }
        if (!isset($_SESSION)) {
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
        $_SESSION['user']->picture = $user->picture;
    }

    public function forget_password(array $input)
    {
        $user = $this->repository->getUserByUsername($input['email']);
        $token = $this->tokenService->createToken($user->identifier);
        $url = "http://" . $_SERVER['SERVER_NAME'] . "/reset_password/$token";
        $this->mailService->sendEmail(
            [
            'reply_to' => $this->configService->getOwnerMailContact(),
            'recipient' => [
                'name' => $user->username,
                'email' => $user->email
            ],
            'subject' => 'Mot de passe oublié',
            'template' => 'forget',
            'template_data' => [
                'url' => $url,
                'name' => $user->username,
            ],
            'success_message' => 'Un e-mail vous a été envoyé pour réinitialiser votre mot de passe <br> Veuillez cliquer sur le lien contenu dans le mail pour réinitialiser votre mot de passe (expire après 30mn).',], 
            [],
            true,
            "Si vous ne parvenez pas à lire ce message, veuillez copier/coller le lien suivant dans votre navigateur: $url"
        );
    }

    public function reset_password($user, $post)
    {
        if ($post['password'] !== $post['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        $user->setPassword($post['password']);
        $success = $this->repository->update(
            $user->identifier,
            ['password' => $user->password,]
        );
        if (!$success) {
            throw new \Exception("Impossible de modifier le mot de passe");
        }
        $this->flashServices->success(
            'Mot de passe modifié',
            'Le mot de passe a bien été modifié'
        );
    }

    public function confirm_again()
    {
        $user = $this->repository->findOne($_SESSION['user']->id);
        $user->withExpirationToken();
        if ($user->token == "expired") {
            $token = $this->tokenService->createToken($user->identifier);
            $this->sendConfirmationEmail($user->email, $user->first, $token);
            $this->flashServices->success(
                'Confirmation de compte',
                'Un nouveau lien de confirmation a été envoyé à votre adresse e-mail.'
            );
        } else {
            $this->flashServices->warning(
                'Confirmation de compte',
                'Un lien a déjà été envoyé <br> Vous ne pouvez pas faire plus d\'une demande en moins de 30mn!'
            );
        }
    }

    public function checkUsername($username)
    {
        if (isset($_SESSION["user"]) && ($username == $_SESSION['user']->username || $username == $_SESSION['user']->email)) {
            echo "already";
        } else {
            $user = $this->repository->getUserByUsername($username);
            if ($user->email == "") {
                echo "available";
            } else {
                echo "unavailable";
            }
        }
    }

    public function sendConfirmationEmail($email , $first, $token)
    {
        $url = "http://" . $_SERVER['SERVER_NAME'] . "/confirmation/$token";
        $this->mailService->sendEmail(
            [
            'reply_to' => $this->configService->getOwnerMailContact(),
            'recipient' => [
                'name' => $first,
                'email' => $email
            ],
            'subject' => 'Validation de votre compte',
            'template' => 'activate',
            'template_data' => [
                'name' => $first,
                'url' => $url,
                'cs_site_name' => $this->configService->getByName("cs_site_name")
            ],
            'success_message' => 'Un mail de confirmation vous a été envoyé. <br>Veuillez cliquer sur le lien contenu dans le mail pour valider votre compte (expire après 30mn).'],
            [],
            true,
            "Si vous ne parvenez pas à lire ce message, veuillez copier/coller le lien suivant dans votre navigateur: $url"
        );
    }
}
