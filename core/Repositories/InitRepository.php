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
        $bdd = new \PDO('mysql:host='. DB_HOST .';dbname=mysql;charset=utf8', DB_USER, DB_PASS);
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
        $statement = $bdd->prepare($sql);
        $statement->execute();
        $this->superGlobals->redirect('home');
    }
}
