<?php

namespace Core\Router;

class Route
{
    private $path;
    private $callable;
    private $matches = [];

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    public function call()
    {
        if (is_string($this->callable)) {
            $params = explode('@', $this->callable);
            if ($params[1] == 'update') {
                $shm = shm_attach(SHM_HTTP_REFERER);
                if (!shm_has_var($shm, 1)) {
                    shm_put_var($shm, 1, $_SERVER['HTTP_REFERER']);
                }
            } else {
                $shm = shm_attach(SHM_HTTP_REFERER);
                if (shm_has_var($shm, 1)) {
                    shm_remove_var($shm, 1);
                }
            }
            if (count($params) == 3) {
                $controller = "Core\\Controllers\\" . $params[0] . "Controller";
            } else {
                $controller = "Application\\Controllers\\" . $params[0] . "Controller";
            }
            $controller = new $controller();
            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    public function getUrl($params)
    {
        $path = $this->path;
        foreach ($params as $k => $v) {
            $path = str_replace(":$k", $v, $path);
        }
        return $path;
    }
}
