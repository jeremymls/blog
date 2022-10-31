<?php

namespace Core\Repositories;

use Core\Middleware\Superglobals;

require_once 'src/config/database.php';

class InitRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_database()
    {
        $dbData = Superglobals::getInstance()->getDatabase();
        $bdd = new \PDO('mysql:host=' . $dbData['host'] . ';dbname=mysql;charset=utf8', $dbData['user'], $dbData['pass']);
        $sql = "CREATE DATABASE IF NOT EXISTS " . $dbData['name'];
        $statement = $bdd->prepare($sql);
        $statement->execute();
        $this->superGlobals->redirect('home');
    }
}
