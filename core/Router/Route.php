<?php

namespace Core\Router;

use Core\Middleware\Session\PHPSession;
use Core\Middleware\Superglobals;

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
            $session = PHPSession::getInstance();
            $temp = $session->get('temp_last_url');
            if ($temp !== null && $temp > 0) {
                $session->set('temp_last_url', $temp - 1);
            } else {
                $session->delete('last_url');
                $session->delete('temp_last_url');
            }
            if ($params[1] == 'update' || 
                $params[1] == 'login'
            ) {
                if ($temp === null) {
                    $session->set('temp_last_url', 1);
                    if (!$session->get('last_url')) {
                        $session->set('last_url', Superglobals::getInstance()->getPathReferer());
                    }
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
