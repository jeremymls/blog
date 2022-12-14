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

    public static function getInstance(): UserSession
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new UserSession();
        }
        return self::$instances[$cls];
    }

    public function isAdmin()
    {
        return ($this->get("user") && $this->get("user")->role == "admin") ? true : false;
    }

    public function isValidate()
    {
        return $this->getUserParam("validated_email") ? true : false;
    }

    public function isUser()
    {
        return ($this->get("user")) ? true : false;
    }

    public function getUser()
    {
        return $this->get("user");
    }

    public function getUserParam($param)
    {
        $user = $this->getUser();
        if ($user) {
            return $user->$param;
        }
        return null;
    }

    public function setUser(User $user)
    {
        $this->set("user", $user);
    }

    public function setUserParam(string $param, $value)
    {
        $user = $this->getUser();
        $user->$param = $value;
        $this->setUser($user);
    }


}