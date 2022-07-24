<?php

namespace Application\Core;

use Application\Controllers\ErrorExceptionController;
use Application\Controllers\HomeController;
use Exception;

class Router
{

// public function __construct($routes) {
//   $this->registerAll($routes);
// }

  public function add($action, $ctrl, $fx, $opt= null) {
    if ($_GET['action'] === $action) {
      $identifier = null;
      if (isset($_GET['id'])) {
        // id is set and is a number
        if ($_GET['id'] > 0){
          $identifier = $_GET['id'];
        } else {
          throw new Exception('Aucun identifiant de billet envoyé');
        }
        // id and post are set
        if (!empty($_POST)) {
          $post = $_POST;
          return ["found",(new $ctrl())->$fx($identifier, $post)];
        }
        return ["found",(new $ctrl())->$fx($identifier)];
      }
      // post only is set
      if (!empty($_POST)) {
        $post = $_POST;
        if ($opt) {
          return ["found",(new $ctrl())->$fx($opt, $post)];
        }
        return ["found",(new $ctrl())->$fx($post)];
      }
      // filter option is set
      if (isset($_GET['filter'])) {
        return ["found",(new $ctrl())->$fx($_GET['filter'])];
      }
      if ($opt != null) {
        return ["found",(new $ctrl())->$fx($opt)];
      }
      /* if (isset($_GET['flush'])) {
        $params[] = $_GET['flush'];
      }*/
      // default case
      return ["found",(new $ctrl())->$fx()];
    } else {
      return "not found";
    }
  }

  public function getRoute($routes) {
    try {
      $success = false;
      if (isset($_GET['action']) && $_GET['action'] !== '') {
        foreach($routes as $action => $route) {
          $options = isset($route['opt']) ? $route['opt'] : [];
          $success = $this->add($action, $route['ctrl'], $route['fx'], $options);
          if ($success[0] === "found") {
            break;
          }
        }
        // Error 404
        if ($success === "not found") {
          throw new Exception("La page que vous recherchez n'existe pas.", 404);
        }
      } else {
        // show homepage
        return (new HomeController())->execute();
      }
    } catch (Exception $err) {
      (new ErrorExceptionController())->execute($err);
    }
  }
}