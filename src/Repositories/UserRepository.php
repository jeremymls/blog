<?php

namespace Application\Repositories;

use Core\Repositories\Repository;
use Application\Models\User;
use Core\Lib\Singleton;

class UserRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
    }

    public function getUserByUsername($user): User
    {
        $user = $this->findBy('username = ? OR email = ?', [$user, $user]);
        return $user;
    }

    public function checkUsername($username)
    {
        $sql = "SELECT * FROM " . $this->model::TABLE . " WHERE username = ? OR email = ?";
        $statement = Singleton::getConnection()->prepare($sql);
        $statement->execute([$username, $username]);
        $row = $statement->fetch();
        if ($row === false) {
            echo true;
        } else {
            echo false;
        }
    }
}
