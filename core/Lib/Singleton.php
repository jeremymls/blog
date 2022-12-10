<?php

namespace Core\Lib;

use Core\Middleware\Session\PHPSession;
use Core\Middleware\Superglobals;

class Singleton
{
    private static $instances = [];
    public static ?\PDO $database = null;

    public function __construct()
    {
    }

    /**
     * This is the static method that controls the access to the singleton
     * instance. On the first run, it creates a singleton object and places it
     * into the static field. On subsequent runs, it returns the client existing
     * object stored in the static field.
     *
     * This implementation lets you subclass the Singleton class while keeping
     * just one instance of each subclass around.
     */
    public static function getConnection(): \PDO
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = self::newConnection();
        }

        return self::$instances[$cls];
    }

    private static function newConnection(): \PDO
    {
        if (self::$database === null) {
            $superglobals = Superglobals::getInstance();
            $dbData = $superglobals->getDatabase();
            try {
                self::$database = new \PDO('mysql:host=' . $dbData['host'] . ';dbname=' . $dbData['name'] . ';charset=utf8', $dbData['user'], $dbData['pass']); // todo: Port
                self::$database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                if ($e->getCode() == 1049) {
                    PHPSession::getInstance()->set('safe_mode', true);
                    $superglobals->redirect('new');
                }
                echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
                // 1049 = database not found
                // 1045 = bad credentials
            }
        }
        return self::$database;
    }
    /**
     * Singletons should not be cloneable.
     */
    protected function __clone()
    { 
    }

    /**
     * Singletons should not be restorable from strings.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}
