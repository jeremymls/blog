<?php
namespace Core\Middleware;

use Application\config\Routes;

class Superglobals
{
    private $_SERVER;
    private $_GET;
    private $_POST;
    private $method;
    private $path;
    

    public function __construct()
    {
        $this->define_superglobals();
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath($name = null, $params = [])
    {
        if ($name) {
            $routes = new Routes();
            return $this->path . "/" .$routes->getUrl($name, $params);
        }
        return $this->path . "/" . trim($_SERVER['REQUEST_URI'], '/');
    }

    public function getPathWithoutGet()
    {
        return strtok($this->getPath(), '&');
    }

    public function redirect($name, $params = [], $anchor = null)
    {
        $url = $this->getPath($name, $params);
        if ($anchor) {
            $url .= '#' . $anchor;
        }
        header('Location: ' . $url);
    }

    public function redirectLastUrl()
    {
        header('Location: ' . $this->getPathReferer());
    }

    public function getPathReferer()
    {
        return $this->referer;
    }

    public function getGet($key = null)
    {
        if ($key) {
            if (array_key_exists($key, $this->_GET)) {
                return $this->_GET[$key];
            }
            return null;
        }
        return $this->_GET;
    }

    public function getPost($key = null)
    {
        if ($key) {
            if (array_key_exists($key, $this->_POST)) {
                return $this->_POST[$key];
            }
            return null;
        }
        return $this->_POST;
    }

    public function getPrefix($key)
    {
        if (array_key_exists($key, $this->_POST)) {
            return substr($this->_POST[$key], 0, 3);
        }
        $this->getPost($key);
        $prefix = $this->getGet('prefix');
        if ($prefix) {
            return $prefix;
        }
        return null;
    }
    
    public function getServer($key)
    {
        if (array_key_exists($key, $this->_SERVER)) {
            return $this->_SERVER[$key];
        }
        return null;
    }

    public function isExistGet($key)
    {
        return array_key_exists($key, $this->_GET);
    }
    
    public function isExistPost($key)
    {
        return array_key_exists($key, $this->_POST);
    }
    
    public function isExistServer($key)
    {
        return array_key_exists($key, $this->_SERVER);
    }

    public function setPost($key, $value)
    {
        $this->_POST[$key] = $value;
    }

    public function isExistPicture($entityTable = null)
    {
        return (isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE && $entityTable != "tokens");
    }

    public function getPicture()
    {
        if ($this->isExistPicture()) {
            return $_FILES['picture'];
        }
    }

    private function define_superglobals()
    {
        $this->_SERVER = (isset($_SERVER)) ? $_SERVER : null;
        $this->_GET = (isset($_GET)) ? $_GET : null;
        $this->_POST = (isset($_POST)) ? $_POST : null;
        $this->method = (isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : null;
        $this->referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null;
        $this->path = (isset($_SERVER["REQUEST_SCHEME"], $_SERVER["HTTP_HOST"] )) ? 
            ($_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]) : null;
        
    }
}