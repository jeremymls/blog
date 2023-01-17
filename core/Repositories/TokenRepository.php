<?php

namespace Core\Repositories;

use Core\Models\Token;
use Application\Models\User;
use Application\Repositories\UserRepository;

/**
 * TokenRepository
 */
class TokenRepository extends Repository
{
    protected $model;
    
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Token();
    }

    /**
     * getUserByToken
     * 
     * Get the user by token
     *
     * @param  string $token Token
     * @return User User
     */
    public function getUserByToken(string $token): User
    {
        $statement = $this->getSelectStatementByModel("WHERE token = ?", [$token]);
        $row = $statement->fetch();
        if ($row === false) {
            throw new \Exception("Impossible de trouver l'utilisateur ! <br> Vérifiez votre token");
        }
        $token = $this->createEntity($row);
        if ($token->expiration_date < date("Y-m-d H:i:s")) {
            throw new \Exception("Le token est expiré ! <br> Veuillez renouveler la demande", 999);
        }
        $token->with('user_id', UserRepository::class);
        $user = $token->user_id;
        return $user;
    }
}
