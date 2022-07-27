<?php
if(! isset($_SESSION)){
    session_start();
}
require_once 'vendor/autoload.php';
require_once 'config/routes.php';
define('ROOT', __DIR__);

use Core\Router;

$r = new Router();
$r->getRoute($routes);