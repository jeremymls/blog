<?php

namespace Core;

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
            $controller = "Application\\Controllers\\" . $params[0]."Controller";
            $controller = new $controller();
            die(var_dump(call_user_func_array([$controller, $params[1]], $this->matches)));
            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            die(var_dump(call_user_func_array($this->callable, $this->matches)));
            return call_user_func_array($this->callable, $this->matches);
        }
    }
}