<?php

namespace Application\Services;

use Application\Models\User;
use Application\Repositories\CommentRepository;
use Core\Middleware\Session\UserSession;
use Core\Services\MailService;
use Core\Services\ConfigService;
use Core\Services\EntityService;
use Core\Services\TokenService;

class UserService extends EntityService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
        $this->userSession = new UserSession();
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
        if ($this->userSession->isAdmin()) {
            $this->superglobal->redirect('admin:users');
        } else {
            $this->userSession->setUser($user);
        }
    }

    public function getData($id)
    {
        if (isset($id) && $this->userSession->isAdmin()) {
            $id = (int) $id;
        } else {
            if ($this->userSession->isUser()) {
                $id = $this->userSession->getUserParam("identifier");
            } else {
                $this->superglobal->redirect('login');
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
        $id = $userId ?? $this->userSession->getUserParam("identifier");
        $this->update(
            $id, 
            $input,
            'L\'utilisateur '. $input['email'] .' a bien été modifié'
        );
        if ($this->userSession->isAdmin() && isset($userId)) {
            $this->superglobal->redirect('admin:users');
        } else {
            $user = $this->repository->getUserByUsername($input['email']);
            $this->userSession->setUser($user);
        }
    }

    public function login(array $input)
    {
        ['identifiant' => $identifiant, 'password' => $password] = $input;
        $user = $this->repository->getUserByUsername($identifiant);
        if (!$user->comparePassword($user->password, $password)) {
            throw new \Exception("Mot de passe incorrect !");
        }
        $this->userSession->setUser($user);
        $this->flashServices->success(
            'Connexion réussie',
            'Vous êtes connecté(e) !'
        );
    }

    public function logout()
    {
        $this->userSession->delete('user');
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
            $this->update($user->identifier, ['validated_email' => 1], "", true, false);
            $user->validated_email = "1";
            $this->userSession->setUser($user);
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

    public function edit_mail($input)
    {
        if ($input['email'] !== $input['retape']) {
            throw new \Exception('Les adresses ne correspondent pas.');
        }
        $success = $this->repository->update(
            $this->userSession->getUserParam("identifier"),
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
        $user = $this->repository->getUserByUsername($input['email']);
        $token = $this->tokenService->createToken($user->identifier);
        $this->sendConfirmationEmail($input['email'], $user->first, $token);
        $this->userSession->setUserParam("email", $input['email']);
    }

    public function edit_password(array $input)
    {
        $id = $this->userSession->getUserParam("identifier");
        $user = $this->repository->findOne($id);        
        if ($input['password'] !== $input['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        if (!$user->comparePassword($user->password, $input['currentPassword'])) {
            throw new \Exception('Ce n\'est le bon mot de passe actuel.');
        }
        $user->setPassword($input['password']);
        $success = $this->repository->update($id,['password' => $user->password,]);
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
        $success = $this->repository->update($this->userSession->getUserParam("identifier"), ['picture' => null]);
        if (!$success) {
            throw new \Exception("Impossible de supprimer la photo de profil");
        }
        $this->flashServices->success(
            'Photo de profil supprimée',
            'La photo de profil a bien été supprimée'
        );
        $this->userSession->setUserParam("picture", "");
    }

    public function forget_password(array $input)
    {
        $user = $this->repository->getUserByUsername($input['email']);
        $token = $this->tokenService->createToken($user->identifier);
        $url = $this->superglobals->getPath('reset_password', ['token' => $token]);
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
        $user = $this->repository->findOne($this->userSession->getUserParam("identifier"));
        $user->withExpirationToken();
        if ($user->token == "expired") {
            $token = $this->tokenService->createToken($user->identifier);
            $this->sendConfirmationEmail($user->email, $user->first, $token);
        } else {
            $this->flashServices->warning(
                'Confirmation de compte',
                'Un lien a déjà été envoyé <br> Vous ne pouvez pas faire plus d\'une demande en moins de 30mn!'
            );
        }
    }

    public function checkUsername($username)
    {
        if ($this->userSession->isUser() && ($username == $this->userSession->getUserParam("username") || $username == $this->userSession->getUserParam("email"))) {
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
        $url = $this->superglobals->getPath('confirmation', ['token' => $token]);
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
