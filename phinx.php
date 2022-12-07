<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'blog_test',
            'user' => 'root',
            'pass' => 'root',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'test' => [
            'adapter' => 'sqlite',
            'memory' => true,
            'name' => 'blog_test',
        ]
    ],
    'version_order' => 'creation'
];
