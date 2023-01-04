<?php
namespace Core\Middleware;

use Application\config\Routes;

class Superglobals
{
    private static $instances = [];
    private $_SERVER;
    private $_GET;
    private $_POST;
    private $_METHOD;
    private $_REFERER;
    private $_ENV;
    private $_PATH;
    private $_ASSETS;
    

    public function __construct()
    {
        $this->define_superglobals();
    }

    public static function getInstance(): Superglobals
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new Superglobals();
        }
        return self::$instances[$cls];
    }

    private function define_superglobals()
    {
        $this->_SERVER = (isset($_SERVER)) ? $_SERVER : null;
        $this->_GET = (isset($_GET)) ? $_GET : null;
        $this->_POST = (isset($_POST)) ? $_POST : null;
        $this->_ENV = (isset($_ENV)) ? $_ENV : null;
        $this->_PATH = (isset($this->_ENV['SITE_URL'])) ? $this->_ENV['SITE_URL'] : null;
        $this->_ASSETS = (isset($this->_ENV['ASSETS_PATH'])) ? $this->_ENV['ASSETS_PATH'] : null;
        $this->_METHOD = (isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : null;
        $this->_REFERER = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $this->_PATH;
    }

    public function getMethod()
    {
        return $this->_METHOD;
    }

    public function getPath($name = null, $params = [])
    {
        if ($name) {
            $routes = new Routes();
            return $this->_PATH . "/" .$routes->getUrl($name, $params);
        }
        return $this->_PATH . "/" . trim($_SERVER['REQUEST_URI'], '/');
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
        return $this->_REFERER;
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

    public function setCookie($key, $value)
    {
        setcookie($key, $value, time() + 3, '/');
    }

    public function getCookie($key)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }
        return null;
    }

    public function isExistPicture($entityTable = null)
    {
        // return (isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE && $entityTable != "tokens");
        return (
            count($_FILES) > 0 && 
            $entityTable != "tokens" &&
            ((isset($_FILES['picture']['error']) && $_FILES['picture']['error'] !== 4) ||
            (isset($_FILES['value']['error']) && $_FILES['value']['error'] !== 4))
        );
    }

    public function getPicture($key = 'picture')
    {
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }
        return null;
    }

    public function asset($path)
    {
        return $this->_ASSETS . $path;
    }

    public function setAppEnv($app_env)
    {
        $this->_ENV['APP_ENV'] = $app_env;
    }

    public function getAppEnv()
    {
        return $this->getEnv('APP_ENV');
    }

    public function getSecretKey()
    {
        return $this->getEnv('SECRET_KEY');
    }

    public function getDatabase($app_env = null)
    {
        if (!$app_env) {
            $app_env = $this->getAppEnv();
        }
        $database['host'] = $this->getEnv($app_env . '_DB_HOST');
        $database['name'] = $this->getEnv($app_env . '_DB_NAME');
        $database['user'] = $this->getEnv($app_env . '_DB_USER');
        $database['pass'] = $this->getEnv($app_env . '_DB_PASS');
        $database['port'] = $this->getEnv($app_env . '_DB_PORT');
        return $database;
    }

    private function getEnv($key)
    {
        if (array_key_exists($key, $this->_ENV)) {
            return $this->_ENV[$key];
        }
        return null;
    }

    public function getHost()
    {
        return $this->_PATH.'/';
    }
}