<?php
namespace Core\Middleware;

use Application\config\Routes;

/**
 * Superglobals
 * 
 * Manage the superglobals
 */
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

    /**
     * __construct
     *
     * Launch the define_superglobals method
     */
    public function __construct()
    {
        $this->define_superglobals();
    }

    /**
     * Singleton
     */
    public static function getInstance(): Superglobals
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new Superglobals();
        }
        return self::$instances[$cls];
    }

    /**
     * define_superglobals
     * 
     * Define the superglobals
     */
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

    /**
     * getMethod
     * 
     * Get the request method
     *
     * @return string|null The request method
     */
    public function getMethod()
    {
        return $this->_METHOD;
    }

    /**
     * getPath
     * 
     * Get the path of the current request or the path of a named route
     *
     * @param  string $name The name of the route
     * @param  array $params The parameters of the route
     * @return string The path
     */
    public function getPath($name = null, $params = [])
    {
        if ($name) {
            $routes = new Routes();
            return $this->_PATH . "/" .$routes->getUrl($name, $params);
        }
        return $this->_PATH . "/" . trim($_SERVER['REQUEST_URI'], '/');
    }

    /**
     * getPathWithoutGet
     * 
     * Get the path of the current request without the GET parameters
     *
     * @return string The path
     */
    public function getPathWithoutGet()
    {
        return strtok($this->getPath(), '&');
    }

    /**
     * redirect
     * 
     * Redirect to a named route
     *
     * @param  string $name The name of the route
     * @param  array $params The parameters of the route
     * @param  string|null $anchor The anchor
     */
    public function redirect($name, $params = [], $anchor = null)
    {
        $url = $this->getPath($name, $params);
        if ($anchor) {
            $url .= '#' . $anchor;
        }
        header('Location: ' . $url);
    }

    /**
     * redirectLastUrl
     * 
     * Redirect to the last url
     */
    public function redirectLastUrl()
    {
        header('Location: ' . $this->getPathReferer());
    }

    /**
     * getPathReferer
     * 
     * Get the path of the last url
     *
     * @return string|null The path of the last url
     */
    public function getPathReferer()
    {
        return $this->_REFERER;
    }

    /**
     * getGet
     * 
     * Get the value of a GET parameter or all the GET parameters
     *
     * @param  string|null $key The key of the GET parameter
     * @return mixed The value of the GET parameter or all the GET parameters
     */
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

    /**
     * getPost
     * 
     * Get the value of a POST parameter or all the POST parameters
     * 
     * @param  string|null $key The key of the POST parameter
     * @return mixed The value of the POST parameter or all the POST parameters
     */
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

    /**
     * getPrefix
     * 
     * Get the prefix of a POST parameter
     * 
     * @param  string $key The key of the POST parameter
     * @return string|null The prefix of the POST parameter
     */
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

    /**
     * setPost
     * 
     * Set the value of a POST parameter
     *
     * @param  string $key The key of the POST parameter
     * @param  mixed $value The value of the POST parameter
     */
    public function setPost($key, $value)
    {
        $this->_POST[$key] = $value;
    }

    /**
     * setCookie
     * 
     * Set a cookie with a 3 seconds expiration time
     *
     * @param  string $key The key of the cookie
     * @param  mixed $value The value of the cookie
     */
    public function setCookie($key, $value)
    {
        setcookie($key, $value, time() + 3, '/');
    }

    /**
     * getCookie
     * 
     * Get the value of a cookie
     *
     * @param  string $key The key of the cookie
     * @return string|null The value of the cookie
     */
    public function getCookie($key)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }
        return null;
    }

    /**
     * isExistPicture
     * 
     * Check if an image is ready to upload
     *
     * @param  ?string $entityTable The name of the entity table
     * @return bool True if an image is ready to upload, false otherwise
     */
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

    /**
     * getPicture
     * 
     * Get the picture to upload
     *
     * @param  string $key The key of the picture
     * @return mixed The picture to upload
     */
    public function getPicture($key = 'picture')
    {
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }
        return null;
    }

    /**
     * asset
     * 
     * Get the path of an asset
     *
     * @param  string $path The path of the asset
     * @return string The path of the asset
     */
    public function asset($path)
    {
        return $this->_ASSETS . $path;
    }

    /**
     * setAppEnv
     * 
     * Set the environment of the application
     *
     * @param  string $app_env The environment of the application
     */
    public function setAppEnv($app_env)
    {
        $this->_ENV['APP_ENV'] = $app_env;
    }

    /**
     * getAppEnv
     * 
     * Get the environment of the application
     *
     * @return string The environment of the application
     */
    public function getAppEnv()
    {
        return $this->getEnv('APP_ENV');
    }

    /**
     * getSecretKey
     * 
     * Get the secret key of the application
     *
     * @return string The secret key of the application
     */
    public function getSecretKey()
    {
        return $this->getEnv('SECRET_KEY');
    }

    /**
     * getDatabase
     * 
     * Get the database configuration
     *
     * @param  string $app_env The environment of the application
     * @return mixed The database configuration
     */
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

    /**
     * getEnv
     * 
     * Get the value of an environment variable
     *
     * @param  string $key The key of the environment variable
     * @return string|null The value of the environment variable
     */
    private function getEnv($key)
    {
        if (array_key_exists($key, $this->_ENV)) {
            return $this->_ENV[$key];
        }
        return null;
    }

    /**
     * getHost
     * 
     * Get the host of the application
     *
     * @return string The host of the application
     */
    public function getHost()
    {
        return $this->_PATH.'/';
    }
}