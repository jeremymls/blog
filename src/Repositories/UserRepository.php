<?php

namespace Application\Repositories;

use Core\Repositories\Repository;
use Application\Models\User;

/**
 * UserRepository
 * 
 * User Repository
 */
class UserRepository extends Repository
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
    }

    /**
     * getUserByUsername
     * 
     * Get user by username or email
     *
     * @param  string $user
     * @return User
     */
    public function getUserByUsername($user): User
    {
        $user = $this->findBy('username = ? OR email = ?', [$user, $user]);
        return $user;
    }
}
