<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Repositories
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Repositories;

use Core\Repositories\Repository;
use Application\Models\User;

/**
 * UserRepository
 *
 * User Repository
 *
 * @category Application
 * @package  Application\Repositories
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
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
     * Get User By Username
     *
     * Get user by username or email
     *
     * @param string $user Pseudo or email
     *
     * @return User
     */
    public function getUserByUsername($user): User
    {
        $user = $this->findBy('username = ? OR email = ?', [$user, $user]);
        return $user;
    }
}
