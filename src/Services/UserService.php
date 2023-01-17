<?php

namespace Application\Services;

use Application\Models\User;
use Application\Repositories\CommentRepository;
use Core\Middleware\Session\UserSession;
use Core\Services\MailService;
use Core\Services\ConfigService;
use Core\Services\CsrfService;
use Core\Services\EntityService;
use Core\Services\TokenService;
use stdClass;

/**
 * UserService
 * 
 * User Service
 */
class UserService extends EntityService
{
    private $userSession;
    private $tokenService;
    private $mailService;
    private $configService;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
        $this->userSession = UserSession::getInstance();
        $this->tokenService = new TokenService();
        $this->mailService = new MailService();
        $this->configService = new ConfigService();
    }

    /**
     * register
     * 
     * Register a new user
     * 
     * @param  array $input
     */
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
            $this->superglobals->redirect('admin:users');
        } else {
            $this->userSession->setUser($user);
        }
    }

    /**
     * getData
     * 
     * Get the user data
     * 
     * @param  mixed $id
     * @return mixed
     */
    public function getData($id)
    {
        if ($id != null && $this->userSession->isAdmin()) {
            $id = (int) $id;
        } else if ($this->userSession->isUser()) {
            $id = $this->userSession->getUserParam("identifier");
        }
        if ($id) {
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
        } else {
            $this->superglobals->redirect('login');
        }
    }

    /**
     * updateUser
     * 
     * Update the user data
     * 
     * @param  array $input
     * @param  mixed $userId
     */
    public function updateUser(array $input, $userId = null)
    {
        $id = $userId ?? $this->userSession->getUserParam("identifier");
        $this->update(
            $id, 
            $input,
            'L\'utilisateur '. $input['email'] .' a bien été modifié'
        );
        if ($this->userSession->isAdmin() && isset($userId)) {
            $this->superglobals->redirect('admin:users');
        } else {
            $user = $this->repository->getUserByUsername($input['email']);
            $this->userSession->setUser($user);
        }
    }

    /**
     * login
     * 
     * Login the user
     * 
     * @param  array $input
     */
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

    /**
     * logout
     * 
     * Logout the user
     */
    public function logout()
    {
        $this->userSession->delete('user');
        $this->flashServices->danger(
            'Déconnexion',
            'Vous êtes déconnecté(e) !'
        ); 
    }

    /**
     * confirmation
     * 
     * Confirmation of the user email
     * 
     * @param  string $token
     */
    public function confirmation($token)
    {
        $params = $this->tokenService->getUserByToken($token);
        $user = $params['user'];
        $validation = new stdClass();
        $validation->validated_email = 1;
        $validation->csrf_token = CsrfService::getInstance()->generateToken();
        if ($user->validated_email == "" || $user->validated_email == null) {
            $this->update($user->identifier, $validation, "", false, false);
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

    /**
     * edit_mail
     * 
     * Edit the user email
     * 
     * @param  array $input
     */
    public function edit_mail($input)
    {
        if ($input['email'] !== $input['retape']) {
            throw new \Exception('Les adresses ne correspondent pas.');
        }
        $user = new stdClass();
        $user->email = $input['email'];
        $user->validated_email = 0;
        $user->csrf_token = $input['csrf_token'];
        $success = $this->repository->update($this->userSession->getUserParam("identifier"),$user);
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

    /**
     * edit_password
     * 
     * Edit the user password
     * 
     * @param  array $input
     */
    public function edit_password(array $input)
    {
        if ($input['password'] !== $input['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        $id = $this->userSession->getUserParam("identifier");
        $user = $this->repository->findOne($id);        
        if (!$user->comparePassword($user->password, $input['currentPassword'])) {
            throw new \Exception('Ce n\'est le bon mot de passe actuel.');
        }
        $newPass = new User();
        $newPass->password = $input['password'];
        $newPass->csrf_token = $input['csrf_token'];
        $success = $this->repository->update($id, $newPass);
        if (!$success) {
            throw new \Exception("Impossible de modifier le mot de passe");
        }
        $this->flashServices->success(
            'Mot de passe modifié',
            'Le mot de passe a bien été modifié'
        );
    }

    /**
     * delete_picture
     * 
     * Delete the user picture
     */
    public function delete_picture()
    {
        $entity = new User();
        $entity->picture = "";
        $entity->csrf_token = $this->superglobals->getPost('csrf_token');
        $success = $this->repository->update($this->userSession->getUserParam("identifier"), $entity);
        if (!$success) {
            throw new \Exception("Impossible de supprimer la photo de profil");
        }
        $this->flashServices->success(
            'Photo de profil supprimée',
            'La photo de profil a bien été supprimée'
        );
        $this->userSession->setUserParam("picture", "");
    }

    /**
     * forget_password
     * 
     * Send an email to the user to reset his password
     * 
     * @param  array $input
     */
    public function forget_password(array $input)
    {
        $user = $this->repository->getUserByUsername($input['email']);
        if ($user->email == null) {
            throw new \Exception("Cet utilisateur n'existe pas");
        }
        $checkToken = $this->tokenService->getAll('where user_id = ?', [$user->identifier], "", null, "ASC")['tokens'];
        if (count($checkToken) > 0 && $checkToken[0]->expiration_date > date("Y-m-d H:i:s")) {
            throw new \Exception("Un mail de réinitialisation a déjà été envoyé à cette adresse.<br> Veuillez vérifier vos spams ou réessayer dans 30mn.");
        } else {
            $token = $this->tokenService->createToken($user->identifier);
        }
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

    /**
     * reset_password
     * 
     * Reset the user password
     * 
     * @param  mixed $user
     * @param  array $post
     */
    public function reset_password($user, $post)
    {
        if ($post['password'] !== $post['passwordConfirm']) {
            throw new \Exception('Les mots de passe ne correspondent pas.');
        }
        $newPass = new stdClass();
        $newPass->password = $post['password'];
        $newPass->csrf_token = $post['csrf_token'];
        $success = $this->repository->update(
            $user->identifier,
            $newPass
        );
        if (!$success) {
            throw new \Exception("Impossible de modifier le mot de passe");
        }
        $this->flashServices->success(
            'Mot de passe modifié',
            'Le mot de passe a bien été modifié'
        );
    }

    /**
     * confirm
     * 
     * Confirm the user account
     */
    public function confirm_again()
    {
        $user = $this->repository->findOne($this->userSession->getUserParam("identifier"));
        $user->withExpirationToken();
        if ($user->getToken() == "expired") {
            $token = $this->tokenService->createToken($user->identifier);
            $this->sendConfirmationEmail($user->email, $user->first, $token);
        } else {
            $this->flashServices->warning(
                'Confirmation de compte',
                'Un lien a déjà été envoyé <br> Vous ne pouvez pas faire plus d\'une demande en moins de 30mn!'
            );
        }
    }

    /**
     * checkUsername
     * 
     * Check if the username is available
     * 
     * @param  string $username
     */
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

    /**
     * sendConfirmationEmail
     * 
     * Send an email to confirm the user account
     * 
     * @param  mixed $email
     * @param  mixed $first
     * @param  mixed $token
     */
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
