<?php

namespace Core\Middleware\Session;

interface SessionInterface
{    
    /**
     * get session value
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return void
     */
    public function get(string $key, $default = null);
    
    /**
     * set session value
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function set(string $key, $value): void;
    
    /**
     * delete session value
     *
     * @param  mixed $key
     * @return void
     */
    public function delete(string $key): void;

    // public function has(string $key): bool;
    // public function clear(): void;
}