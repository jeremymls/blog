<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Middleware\Session
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Middleware\Session;

use Application\Models\User;

/**
 * UserSession
 *
 * Manage the user session
 *
 * @category Core
 * @package  Core\Middleware\Session
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class UserSession extends PHPSession
{
    private static $instances = [];

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Singleton
     *
     * @return UserSession
     */
    public static function getInstance(): UserSession
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new UserSession();
        }
        return self::$instances[$cls];
    }

    /**
     * Is Admin
     *
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->get("user") && $this->get("user")->role == "admin")
        ? true : false;
    }

    /**
     * Is Validate
     *
     * Check if the user has validated his email
     *
     * @return bool
     */
    public function isValidate()
    {
        return $this->getUserParam("validated_email") ? true : false;
    }

    /**
     * Is User
     *
     * Check if the user is logged in
     *
     * @return bool
     */
    public function isUser()
    {
        return ($this->get("user")) ? true : false;
    }

    /**
     * Get User
     *
     * Get the user data from the session
     *
     * @return User
     */
    public function getUser()
    {
        return $this->get("user");
    }

    /**
     * Get User Param
     *
     * Get a user param from the session
     *
     * @param string $param The param to get
     *
     * @return mixed
     */
    public function getUserParam($param)
    {
        $user = $this->getUser();
        if ($user) {
            return $user->$param;
        }
        return null;
    }

    /**
     * Set User
     *
     * Set the user data in the session
     *
     * @param User $user The user data
     *
     * @return void
     */
    public function setUser(User $user)
    {
        $this->set("user", $user);
    }

    /**
     * Set User Param
     *
     * Set a user param in the session
     *
     * @param string $param The param to set
     * @param mixed  $value The value to set
     *
     * @return void
     */
    public function setUserParam(string $param, $value)
    {
        $user = $this->getUser();
        $user->$param = $value;
        $this->setUser($user);
    }
}
