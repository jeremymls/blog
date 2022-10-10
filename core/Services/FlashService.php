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
        $shm = shm_attach(SHM_FLASH);
        $i = 1;
        while (shm_has_var($shm, $i)) {
            $i++;
        }
        shm_put_var($shm, $i, [
            'type' => $type,
            'title' => $title,
            'message' => $message
        ]);
    }
}
