<?php

namespace Core\Services;

use Core\Middleware\Session\PHPSession;

class FlashService
{
    private static $instances = [];
    private $sessionKey = 'flash';
    
    public function __construct()
    {
        $this->session = PHPSession::getInstance();
    }

    public static function getInstance(): FlashService
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new FlashService();
        }
        return self::$instances[$cls];
    }

    public function success(string $title, string $message)
    {
        return $this->send('success', $title, $message);
    }

    public function danger(string $title, string $message)
    {
        return $this->send('danger', $title, $message);
    }

    public function info(string $title, string $message)
    {
        return $this->send('info', $title, $message);
    }

    public function warning(string $title, string $message)
    {
        return $this->send('warning', $title, $message);
    }

    public function template(string $template)
    {
        return $this->send($template);
    }
    
    private function send(string $type, string $title = "", string $message = "")
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message
        ];
        $this->session->set($this->sessionKey, $flash);
    }

    public function get()
    {
        $flash = $this->session->get($this->sessionKey, []);
        $this->session->delete($this->sessionKey);
        return $flash;
    }
}
