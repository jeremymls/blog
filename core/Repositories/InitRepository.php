<?php

namespace Core\Repositories;

use Core\Middleware\Superglobals;

/**
 * InitRepository
 * 
 * Manage the database creation
 */
class InitRepository extends Repository
{
    protected $connection;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * create_database
     * 
     * Create the database
     */
    public static function create_database()
    {
        $superglobals = Superglobals::getInstance();
        $dbData = $superglobals->getDatabase();
        $bdd = new \PDO('mysql:host=' . $dbData['host'] . ';dbname=mysql;charset=utf8', $dbData['user'], $dbData['pass']);
        $sql = "CREATE DATABASE IF NOT EXISTS " . $dbData['name'];
        $statement = $bdd->prepare($sql);
        $statement->execute();
    }

    /**
     * delete_database
     * 
     * Delete the database
     */
    public function delete_database()
    {
        // todo : revoir la sécurité
        $superglobals = Superglobals::getInstance();
        $dbData = $superglobals->getDatabase();
        $bdd = new \PDO('mysql:host=' . $dbData['host'] . ';dbname=mysql;charset=utf8', $dbData['user'], $dbData['pass']);
        $sql = "DROP DATABASE IF EXISTS " . $dbData['name'];
        $statement = $bdd->prepare($sql);
        $statement->execute();
    }
}
