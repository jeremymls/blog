<?php

namespace Core\Services;

class FlashService
{
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
        setcookie("flash", "on", time() + 5, "/");
        setcookie("type", $type, time() + 5, "/");
        setcookie("title", $title, time() + 5, "/");
        setcookie("message", $message, time() + 5, "/");
    }
}