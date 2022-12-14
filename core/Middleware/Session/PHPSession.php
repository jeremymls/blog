<?php
namespace Core\Middleware\Session;


class PHPSession implements SessionInterface
{
    private static $instances = [];

    public function __construct()
    {
        $this->ensureStarted();
    }

    public static function getInstance() 
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new PHPSession();
        }
        return self::$instances[$cls];
    }

    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
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

    public function redirectLastUrl($anchor = null)
    {
        
        header('Location: ' . $this->getLastUrl($anchor));
    }

    public function getLastUrl($anchor = null)
    {
        $last_url = $this->get('last_url');
        if ($last_url) {
            $this->delete('last_url');
            return $last_url . ($anchor ? ('#' . $anchor) : '');
        }
        return '/';
    }
}