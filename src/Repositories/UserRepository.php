<?php

namespace Application\Repositories;

use Application\Models\User;

class UserRepository extends Repository
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function getUserByUsername($user): User
    {
        $statement = $this->getSelectStatementByModel("WHERE username = ? OR email = ?", [$user, $user]);
        $row = $statement->fetch();
        if ($row === false) {
            throw new \Exception("Impossible de trouver l'utilisateur !\n Vérifiez votre identifiant");
        }
        $user = $this->createEntity($row);
        return $user;
    }
}