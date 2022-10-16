<?php

namespace Core\Services;

use Core\Middleware\Session\PHPSession;

class FlashService
{
    private $sessionKey = 'flash';
    
    public function __construct()
    {
        $this->session = new PHPSession();
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
    
    private function send(string $type, string $title, string $message)
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
