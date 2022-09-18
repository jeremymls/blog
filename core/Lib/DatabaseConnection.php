<?php

namespace Core\Lib;

include_once('src/config/database.php');

class DatabaseConnection
{
    public static ?\PDO $database = null;

    public function __construct()
    {
        if (self::$database === null) {
            if ((isset($_GET['url']) && $_GET['url'] != 'create_bdd') || !isset($_GET['url'])) {
            try {
                self::$database = new \PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME .';charset=utf8', DB_USER, DB_PASS);
                self::$database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                if ($e->getCode() == 1049) {
                    $_SESSION['safe_mode'] = true;
                    header('Location: /new');
                }
                echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
                // 1049 = database not found
                // 1045 = bad credentials
            }
        }
    }
        return self::$database;
    }
}
