<?php

use Application\config\Routes;

require_once 'vendor/autoload.php';
define('ROOT', __DIR__);
define('SHM_HTTP_REFERER', 1);
(new Routes())->run();
?>