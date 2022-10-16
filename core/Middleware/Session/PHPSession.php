<?php
namespace Core\Middleware\Session;


class PHPSession implements SessionInterface
{
    public function __construct()
    {
        $this->ensureStarted();
    }

    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function delete(string $key)
    {
        unset($_SESSION[$key]);
    }
}