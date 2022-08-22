<?php

namespace Core\Lib;

include_once('src/config/database.php');

class DatabaseConnection
{
    public ?\PDO $database = null;

    public function getConnection(): \PDO
    {
        if ($this->database === null) {
            $this->database = new \PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME .';charset=utf8', DB_USER, DB_PASS);
        }
        return $this->database;
    }
}
