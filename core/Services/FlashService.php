<?php

namespace Core\Services;

use Core\Middleware\Session\PHPSession;

class FlashService
{
    private static $instances = [];
    private $sessionKey = 'flash';
    protected $session;

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

    public function success(string $title, string $message): void
    {
        $this->send('success', $title, $message);
    }

    public function danger(string $title, string $message): void
    {
        $this->send('danger', $title, $message);
    }

    public function info(string $title, string $message): void
    {
        $this->send('info', $title, $message);
    }

    public function warning(string $title, string $message): void
    {
        $this->send('warning', $title, $message);
    }

    public function template(string $template): void
    {
        $this->send($template);
    }

    private function send(string $type, string $title = "", string $message = ""): void
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
