<?php

require_once 'vendor/autoload.php';
require_once 'src/config/default.php';

use Application\config\Routes;
use Core\Controllers\ErrorExceptionController;
use Dotenv\Dotenv;

const ROOT = __DIR__;

$dotenv = Dotenv::createMutable(__DIR__);
$dotenv->load();
try {
    $dotenv->required([
        'APP_ENV', 'SITE_URL', 'ASSETS',
        'PROD_DB_HOST', 'PROD_DB_NAME', 'PROD_DB_USER', 'PROD_DB_PASS', 'PROD_DB_PORT',
        'DEV_DB_HOST', 'DEV_DB_NAME', 'DEV_DB_USER', 'DEV_DB_PASS', 'DEV_DB_PORT',
        'TEST_DB_HOST', 'TEST_DB_NAME', 'TEST_DB_USER', 'TEST_DB_PASS', 'TEST_DB_PORT'
    ]);
} catch (Exception $e) {
    $customMessage = 'Le fichier .env est manquant ou incomplet.';
    (new ErrorExceptionController())->execute($e, $customMessage);
    $noRunRoutes = true;
}
if (!isset($noRunRoutes)) {
    (new Routes())->run();
}
