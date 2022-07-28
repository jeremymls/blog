<?php
if(! isset($_SESSION)){
    session_start();
}
require_once 'vendor/autoload.php';
define('ROOT', __DIR__);
require_once 'src/config/routes.php';