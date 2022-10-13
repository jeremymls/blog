<?php

use Application\config\Routes;

if(! isset($_SESSION)){
    session_start();
}
require_once 'vendor/autoload.php';
define('ROOT', __DIR__);
define('SHM_HTTP_REFERER', 1);
define('SHM_FLASH', 2);
(new Routes())->run();
?>