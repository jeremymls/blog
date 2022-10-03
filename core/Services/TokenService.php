<?php

namespace Core\Services;

use Core\Models\Token;
use Core\Repositories\TokenRepository;

class TokenService extends EntityService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new Token();
        // $this->repository = new TokenRepository();
    }

    public function createToken(string $user_id)
    {
        $token = bin2hex(random_bytes(32));
        $expiration_date = date("Y-m-d H:i:s", time() + (60 * 30));
        $token = $this->validateForm(
            [
            'user_id' => $user_id,
            'token' => $token,
            'expiration_date' => $expiration_date
            ]
        );
        $success = $this->repository->add($token);
        if (!$success) {
            throw new \Exception('Impossible de créer le token !');
        }
        return $token->token;
    }

    public function getUserByToken($token)
    {
        $params = $this->getBy('token = ?', [$token]);
        $token = $params['token'];
        if ($token->expiration_date < date("Y-m-d H:i:s")) {
            throw new \Exception("Le token est expiré ! <br> Veuillez renouveler la demande", 999);
        }
        $params["user"] = $token->user_id;
        return $params;
    }
}
