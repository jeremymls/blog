<?php

/**
 * Created by Jeremy Monlouis
 * php version 7.4.3
 *
 * @category Router
 * @package  Core\Router
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Router;

use Core\Controllers\ErrorExceptionController;
use Core\Middleware\Superglobals;
use Exception;

/**
 * Router
 *
 * Manage the routes
 *
 * @category Router
 * @package  Core\Router
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Router
{
    public $url;
    private $routes = [];
    private $namedRoutes = [];

    /**
     * __construct
     *
     * @param mixed $url Url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get
     *
     * Add a GET route
     *
     * @param string $path     The path
     * @param string $callable The callable 'Controller@Method'
     * @param string $name     The name
     *
     * @return Route
     */
    public function get($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    /**
     * Post
     *
     * Add a POST route
     *
     * @param string $path     The path
     * @param string $callable The callable 'Controller@Method'
     * @param string $name     The name
     *
     * @return Route
     */
    public function post($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    /**
     * Add
     *
     * Add a route
     *
     * @param string $path     The path
     * @param string $callable The callable 'Controller@Method'
     * @param string $name     The name
     * @param string $method   The method (GET, POST)
     *
     * @return Route
     */
    private function add($path, $callable, $name, $method)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if (is_string($callable) && $name === null) {
            $name = $callable;
        }
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    /**
     * Run
     *
     * Run the router
     *
     * @return mixed
     */
    public function run()
    {
        $method = Superglobals::getInstance()->getMethod();
        try {
            if (!isset($this->routes[$method])) {
                throw new Exception('REQUEST_METHOD does not exist');
            }
            foreach ($this->routes[$method] as $route) {
                if ($route->match($this->url)) {
                    return $route->call();
                }
            }
            throw new Exception(
                "La page que vous recherchez n'existe pas.<br>( /" . $this->url . " )",
                404
            );
        } catch (Exception $e) {
            (new ErrorExceptionController())->execute($e);
        }
    }

    /**
     * Url
     *
     * Check if the route exists and return the path
     *
     * @param mixed $name   The name
     * @param mixed $params The params
     *
     * @return string
     */
    public function url($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new Exception(
                "Le nom de route que vous recherchez n'existe pas.",
                404
            );
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }
}
