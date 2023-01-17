<?php

namespace Core\Services;

use Core\Models\Token;
use Core\Repositories\TokenRepository;

/**
 * TokenService
 * 
 * Manage the tokens
 */
class TokenService extends EntityService
{

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Token();
        // $this->repository = new TokenRepository();
    }

    /**
     * createToken
     * 
     * Create a token
     * 
     * @param string $user_id The user id
     * 
     * @return string The token
     */
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

    /**
     * getUserByToken
     * 
     * Get the user by the token
     * 
     * @param string $token The token
     * 
     * @return array Params with the user
     */
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
