<?php
namespace Core\Services;

use Core\Middleware\Session\PHPSession;

class CsrfService
{
    private static $instances = [];

    public function __construct()
    {
        $this->session = PHPSession::getInstance();
    }

    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new CsrfService();
        }
        return self::$instances[$cls];
    }

    private function getToken()
    {
        return $this->session->get("csrf_token");
    }

    public function generateToken()
    {
        if (!$this->getToken()) {
            $csrf_token = bin2hex(random_bytes(32));
            $this->session->set("csrf_token", $csrf_token);
        }
        return $this->getToken();
    }

    public function checkToken($token)
    {
        if ($token === $this->getToken()) {
            return true;
        }
        return false;
    }
}