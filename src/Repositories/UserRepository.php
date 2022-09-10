<?php

namespace Application\Repositories;

use Core\Repositories\Repository;
use Application\Models\User;

class UserRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
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

    public function checkUsername($username)
    {
        $sql = "SELECT * FROM " . $this->model::TABLE . " WHERE username = ? OR email = ?";
        $statement = $this->connection::$database->prepare($sql);
        $statement->execute([$username, $username]);
        $row = $statement->fetch();
        if ($row === false) {
            echo true;
        } else {
            echo false;
        }
    }
}