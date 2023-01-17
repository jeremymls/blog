<?php

namespace Core\Router;

use Core\Controllers\ErrorExceptionController;
use Core\Middleware\Superglobals;
use Exception;

/**
 * Router
 * 
 * Manage the routes
 */
class Router
{

    private $url;
    private $routes = [];
    private $namedRoutes = [];

    /**
     * __construct
     *
     * @param  mixed $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * get
     * 
     * Add a GET route
     * 
     * @param  mixed $path
     * @param  mixed $callable
     * @param  mixed $name
     * @return Route
     */
    public function get($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    /**
     * post
     * 
     * Add a POST route
     *
     * @param  mixed $path
     * @param  mixed $callable
     * @param  mixed $name
     * @return Route
     */
    public function post($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    /**
     * add
     * 
     * Add a route
     *
     * @param  mixed $path
     * @param  mixed $callable
     * @param  mixed $name
     * @param  mixed $method
     * @return Route
     */
    private function add($path, $callable, $name, $method)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if(is_string($callable) && $name === null) {
            $name = $callable;
        }
        if($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    /**
     * run
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
            throw new Exception("La page que vous recherchez n'existe pas.<br>( /".$this->url." )", 404);
        } catch (Exception $e) {
            (new ErrorExceptionController())->execute($e);
        }
    }

    /**
     * url
     * 
     * Get the url of a route by name
     *
     * @param  mixed $name
     * @param  mixed $params
     * @return string Url
     */
    public function url($name, $params = [])
    {
        if(!isset($this->namedRoutes[$name])) {
            throw new Exception("Le nom de route que vous recherchez n'existe pas.", 404);
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }
}
