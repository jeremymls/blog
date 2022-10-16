<?php

namespace Core\Middleware\Session;

interface SessionInterface
{    
    public function get(string $key, $default = null);
    
    public function set(string $key, $value);
    
    public function delete(string $key);

    // public function has(string $key): bool;
    // public function clear(): void;
}