<?php

namespace Core\Lib;

include_once('src/config/database.php');

class DatabaseConnection
{
    public static ?\PDO $database = null;

    public function __construct()
    {
        if (self::$database === null) {
            self::$database = new \PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME .';charset=utf8', DB_USER, DB_PASS);
        }
        return self::$database;
    }
}
