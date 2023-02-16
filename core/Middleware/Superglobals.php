<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Middleware;

use Application\config\Routes;

/**
 * Superglobals
 *
 * Manage the superglobals
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Superglobals
{
    private static $instances = [];
    private $SERVER;
    private $GET;
    private $POST;
    private $ENV;
    private $PATH;
    private $ASSETS;

    /**
     * __construct
     *
     * Launch the defineSuperglobals method
     */
    public function __construct()
    {
        $this->defineSuperglobals();
    }

    /**
     * Singleton
     *
     * @return Superglobals
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
     * Define Superglobals
     *
     * Define the superglobals
     *
     * @return void
     */
    private function defineSuperglobals()
    {
        $this->SERVER = (isset($_SERVER)) ? $_SERVER : null;
        $this->GET = (isset($_GET)) ? $_GET : null;
        $this->POST = (isset($_POST)) ? $_POST : null;
        $this->ENV = (isset($_ENV)) ? $_ENV : null;
        $this->PATH = (isset($this->ENV['SITE_URL']))
        ? $this->ENV['SITE_URL'] : null;
        $this->ASSETS = (isset($this->ENV['ASSETS_PATH']))
        ? $this->ENV['ASSETS_PATH'] : null;
    }

    /**
     * Get Method
     *
     * Get the request method
     *
     * @return string|null The request method
     */
    public function getMethod()
    {
        return $this->getServer("REQUEST_METHOD");
    }

    /**
     * Get Path
     *
     * Get the path of the current request or the path of a named route
     *
     * @param string $name   The name of the route
     * @param array  $params The parameters of the route
     *
     * @return string
     */
    public function getPath($name = null, $params = [])
    {
        if ($name) {
            $routes = new Routes();
            return $this->PATH . "/" . $routes->getUrl($name, $params);
        }
        return $this->PATH . "/" . trim($this->getServer('REQUEST_URI'), '/');
    }

    /**
     * Get Path Without Get
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
     * Redirect
     *
     * Redirect to a named route
     *
     * @param string      $name   The name of the route
     * @param array       $params The parameters of the route
     * @param string|null $anchor The anchor
     *
     * @return void
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
     * Redirect Last Url
     *
     * Redirect to the last url
     *
     * @return void
     */
    public function redirectLastUrl()
    {
        header('Location: ' . $this->getPathReferer());
    }

    /**
     * Get Path Referer
     *
     * Get the path of the last url
     *
     * @return string|null The path of the last url
     */
    public function getPathReferer()
    {
        return $this->getServer("HTTP_REFERER");
    }

    /**
     * Get Get
     *
     * Get the value of a GET parameter or all the GET parameters
     *
     * @param string|null $key The key of the GET parameter
     *
     * @return mixed The value of the GET parameter or all the GET parameters
     */
    public function getGet($key = null)
    {
        if ($key) {
            if (array_key_exists($key, $this->GET)) {
                return $this->GET[$key];
            }
            return null;
        }
        return $this->GET;
    }

    /**
     * Get Post
     *
     * Get the value of a POST parameter or all the POST parameters
     *
     * @param string|null $key The key of the POST parameter
     *
     * @return mixed The value of the POST parameter or all the POST parameters
     */
    public function getPost($key = null)
    {
        if ($key) {
            if (array_key_exists($key, $this->POST)) {
                return $this->POST[$key];
            }
            return null;
        }
        return $this->POST;
    }

    /**
     * Get Prefix
     *
     * Get the prefix of a POST parameter
     *
     * @param string $key The key of the POST parameter
     *
     * @return string|null The prefix of the POST parameter
     */
    public function getPrefix($key)
    {
        if (array_key_exists($key, $this->POST)) {
            return substr($this->POST[$key], 0, 3);
        }
        $this->getPost($key);
        $prefix = $this->getGet('prefix');
        if ($prefix) {
            return $prefix;
        }
        return null;
    }

    /**
     * Set Post
     *
     * Set the value of a POST parameter
     *
     * @param string $key   The key of the POST parameter
     * @param mixed  $value The value of the POST parameter
     *
     * @return void
     */
    public function setPost($key, $value)
    {
        $this->POST[$key] = $value;
    }

    /**
     * Set Cookie
     *
     * Set a cookie with a 3 seconds expiration time
     *
     * @param string $key   The key of the cookie
     * @param mixed  $value The value of the cookie
     *
     * @return void
     */
    public function setCookie($key, $value)
    {
        setcookie($key, $value, time() + 3, '/');
    }

    /**
     * Get Cookie
     *
     * Get the value of a cookie
     *
     * @param string $key The key of the cookie
     *
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
     * Get Server
     *
     * Get the value of a server parameter or all the server parameters
     *
     * @param string|null $key The key of the server parameter
     *
     * @return mixed The value of the server parameter or all the server parameters
     */
    public function getServer($key = null)
    {
        if ($key) {
            if (array_key_exists($key, $this->SERVER)) {
                return $this->SERVER[$key];
            }
            return null;
        }
        return $this->SERVER;
    }

    /**
     * Is Exist Picture
     *
     * Check if an image is ready to upload
     *
     * @param ?string $entityTable The name of the entity table
     *
     * @return bool True if an image is ready to upload, false otherwise
     */
    public function isExistPicture($entityTable = null)
    {
        // return (isset($_FILES['picture'])
            // && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE
            // && $entityTable != "tokens");
        return (
            count($_FILES) > 0 &&
            $entityTable != "tokens" &&
            ((isset($_FILES['picture']['error'])
            && $_FILES['picture']['error'] !== 4) ||
            (isset($_FILES['value']['error']) && $_FILES['value']['error'] !== 4))
        );
    }

    /**
     * Get Picture
     *
     * Get the picture to upload
     *
     * @param string $key The key of the picture
     *
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
     * Asset
     *
     * Get the path of an asset
     *
     * @param string $path The path of the asset
     *
     * @return string The path of the asset
     */
    public function asset($path)
    {
        return $this->ASSETS . $path;
    }

    /**
     * Set App Env
     *
     * Set the environment of the application
     *
     * @param string $app_env The environment of the application
     *
     * @return void
     */
    public function setAppEnv($app_env)
    {
        $this->ENV['APP_ENV'] = $app_env;
    }

    /**
     * Get App Env
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
     * Get Secret Key
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
     * Get Database
     *
     * Get the database configuration
     *
     * @param string $app_env The environment of the application
     *
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
     * Get Env
     *
     * Get the value of an environment variable
     *
     * @param string $key The key of the environment variable
     *
     * @return string|null The value of the environment variable
     */
    private function getEnv($key)
    {
        if (array_key_exists($key, $this->ENV)) {
            return $this->ENV[$key];
        }
        return null;
    }

    /**
     * Get Host
     *
     * Get the host of the application
     *
     * @return string The host of the application
     */
    public function getHost()
    {
        return $this->PATH . '/';
    }
}
