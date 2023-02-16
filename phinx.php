<?php

use Core\Middleware\Superglobals;
$noRunRoutes = true;
include 'index.php';
$superglobals = Superglobals::getInstance();
$dbProd = $superglobals->getDatabase('PROD');
$dbDev = $superglobals->getDatabase('DEV');
$dbTest = $superglobals->getDatabase('TEST');
return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_environment' => 'PROD',
        'PROD' => [
            'adapter' => 'mysql',
            'host' => $dbProd['host'],
            'name' => $dbProd['name'],
            'user' => $dbProd['user'],
            'pass' => $dbProd['pass'],
            'port' => $dbProd['port'],
            'charset' => 'utf8',
        ],
        'DEV' => [
            'adapter' => 'mysql',
            'host' => $dbDev['host'],
            'name' => $dbDev['name'],
            'user' => $dbDev['user'],
            'pass' => $dbDev['pass'],
            'port' => $dbDev['port'],
            'charset' => 'utf8',
        ],
        'TEST' => [
            'adapter' => 'mysql',
            'host' => $dbTest['host'],
            'name' => $dbTest['name'],
            'user' => $dbTest['user'],
            'pass' => $dbTest['pass'],
            'port' => $dbTest['port'],
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
