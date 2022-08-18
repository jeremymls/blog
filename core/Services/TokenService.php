<?php

namespace Core\Services;

use Core\Service;
use Application\Models\Token;

class TokenService extends Service
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new Token();
    }

    public function createToken(string $user_id)
    {
        $token = bin2hex(random_bytes(32));
        $expiration_date = date("Y-m-d H:i:s", time() + (60 * 30));
        $token = $this->validateForm([
            'user_id' => $user_id,
            'token' => $token,
            'expiration_date' => $expiration_date
        ]);
        $success = $this->tokenRepository->add($token);
        if (!$success) {
            throw new \Exception('Impossible de créer le token !');
        }
        return $token->token;
    }

}
