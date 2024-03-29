<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Repositories
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Repositories;

use Core\Models\Token;
use Application\Models\User;
use Application\Repositories\UserRepository;

/**
 * TokenRepository
 *
 * @category Core
 * @package  Core\Repositories
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
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
     * Get User By Token
     *
     * Get the user by token
     *
     * @param string $token Token
     *
     * @return User
     */
    public function getUserByToken(string $token): User
    {
        $statement = $this->getSelectStatementByModel("WHERE token = ?", [$token]);
        $row = $statement->fetch();
        if ($row === false) {
            throw new \Exception(
                "Impossible de trouver l'utilisateur ! <br> Vérifiez votre token"
            );
        }
        $token = $this->createEntity($row);
        if ($token->expiration_date < date("Y-m-d H:i:s")) {
            throw new \Exception(
                "Le token est expiré ! <br> Veuillez renouveler la demande",
                999
            );
        }
        $token->with('user_id', UserRepository::class);
        $user = $token->user_id;
        return $user;
    }
}
