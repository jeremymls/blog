<?php

use Application\config\Routes;

require_once 'vendor/autoload.php';
define('ROOT', __DIR__);
(new Routes())->run();
?>