<?php

namespace Application\Repositories;

use Core\Repository;
use Application\Models\Token;
use Application\Models\User;

class TokenRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Token();
    }

    public function getUserByToken(string $token): User
    {
        $statement = $this->getSelectStatementByModel("WHERE token = ?", [$token]);
        $row = $statement->fetch();
        if ($row === false) {
            throw new \Exception("Impossible de trouver l'utilisateur ! <br> Vérifiez votre token");
        }
        $token = $this->createEntity($row);
        if ($token->expiration_date < date("Y-m-d H:i:s")) {
            throw new \Exception("Le token est expiré ! <br> Veuillez renouveler la demande",999);
        }
        $token->with('user_id', UserRepository::class);
        $user = $token->user_id;
        return $user;
    }
}