<?php
namespace Core\Middleware\Session;

use Application\Models\User;

class UserSession extends PHPSession
{
    private static $instances = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Singleton
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
     * isAdmin
     * 
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->get("user") && $this->get("user")->role == "admin") ? true : false;
    }

    /**
     * isValidate
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
     * isUser
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
     * getUser
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
     * getUserParam
     * 
     * Get a user param from the session
     *
     * @param  string $param
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
     * setUser
     * 
     * Set the user data in the session
     *
     * @param  User $user
     */
    public function setUser(User $user)
    {
        $this->set("user", $user);
    }

    /**
     * setUserParam
     * 
     * Set a user param in the session
     *
     * @param  string $param
     * @param  mixed $value
     */
    public function setUserParam(string $param, $value)
    {
        $user = $this->getUser();
        $user->$param = $value;
        $this->setUser($user);
    }
}