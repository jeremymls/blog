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
}
