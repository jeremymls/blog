<?php

namespace Core\Router;

use Core\Controllers\ErrorExceptionController;
use Exception;

class Router
{

  private $url;
  private $routes = [];

  public function __construct($url)
  {
    $this->url = $url;
  }

  public function get($path, $callable, $name = null)
  {
    return $this->add($path, $callable, $name, 'GET');
  }

public function post($path, $callable, $name = null)
  {
    return $this->add($path, $callable, $name, 'POST');
  }

private function add($path, $callable, $name, $method)
  {
    $route = new Route($path, $callable);
    $this->routes[$method][] = $route;
    if(is_string($callable) && $name === null){
      $name = $callable;
    }
    if($name){
      $this->namedRoutes[$name] = $route;
    }
    return $route;
  }

  public function run()
  {
    try {
      if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
        throw new Exception('REQUEST_METHOD does not exist');
      }
      foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
        if ($route->match($this->url)) {
          return $route->call();
        }
      }
      throw new Exception("La page que vous recherchez n'existe pas.<br>( /".$this->url." )", 404);
    } catch (Exception $e) {
      (new ErrorExceptionController())->execute($e);
    }
  }

  public function url($name, $params = []){
    if(!isset($this->namedRoutes[$name])){
      throw new Exception("Le nom de route que vous recherchez n'existe pas.", 404);
    }
    return $this->namedRoutes[$name]->getUrl($params);
  }
}