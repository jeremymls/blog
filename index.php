<?php

use Application\config\Routes;

require_once 'vendor/autoload.php';
define('ROOT', __DIR__);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'HOST'])->notEmpty();
(new Routes())->run();
?>