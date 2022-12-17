<?php

namespace Core\Repositories;

use Core\Middleware\Superglobals;

class InitRepository extends Repository
{
    protected $connection;

    public function __construct()
    {
        parent::__construct();
    }

    public static function create_database()
    {
        $superglobals = Superglobals::getInstance();
        $dbData = $superglobals->getDatabase();
        $bdd = new \PDO('mysql:host=' . $dbData['host'] . ';dbname=mysql;charset=utf8', $dbData['user'], $dbData['pass']);
        $sql = "CREATE DATABASE IF NOT EXISTS " . $dbData['name'];
        $statement = $bdd->prepare($sql);
        $statement->execute();
    }

    public function create_tables()
    {
        $filePath = "docs/sql/blog.sql";
        if (file_exists($filePath)) {
            $sql = file_get_contents($filePath);
            $statement = $this->connection->prepare($sql);
            $statement->execute();
        } else {
            throw new \Exception("Le fichier $filePath n'existe pas");
        }
    }

    public function delete_database()
    {
        $superglobals = Superglobals::getInstance();
        $dbData = $superglobals->getDatabase();
        $bdd = new \PDO('mysql:host=' . $dbData['host'] . ';dbname=mysql;charset=utf8', $dbData['user'], $dbData['pass']);
        $sql = "DROP DATABASE IF EXISTS " . $dbData['name'];
        $statement = $bdd->prepare($sql);
        $statement->execute();
    }
}
