<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Lib\Connection
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Lib;

use Core\Middleware\Session\PHPSession;
use Core\Middleware\Superglobals;

/**
 * Connection
 *
 * Manage the connection to the database
 *
 * @category Core
 * @package  Core\Lib\Connection
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Connection
{
    private static $instances = [];
    public static ?\PDO $database = null;

    /**
     * Singleton
     *
     * This is the static method that controls the access to the Connexion
     * instance. On the first run, it creates a Connexion object and places it
     * into the static field. On subsequent runs, it returns the client existing
     * object stored in the static field.
     *
     * This implementation lets you subclass the Singleton class while keeping
     * just one instance of each subclass around.
     *
     * @return \PDO
     */
    public static function getConnection(): \PDO
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = self::newConnection();
        }
        return self::$instances[$cls];
    }

    /**
     * New Connection
     *
     * This function creates a new connection to the database
     *
     * @return \PDO
     */
    private static function newConnection(): \PDO
    {
        if (self::$database === null) {
            $superglobals = Superglobals::getInstance();
            $dbData = $superglobals->getDatabase();
            try {
                self::$database = new \PDO(
                    'mysql:host='
                    . $dbData['host']
                    . ';dbname='
                    . $dbData['name']
                    . ';charset=utf8',
                    $dbData['user'],
                    $dbData['pass']
                );
                self::$database->setAttribute(
                    \PDO::ATTR_ERRMODE,
                    \PDO::ERRMODE_EXCEPTION
                );
            } catch (\PDOException $e) {
                if ($e->getCode() == 1049) {
                    PHPSession::getInstance()->set('safe_mode', true);
                    $superglobals->redirect('new');
                }
                throw $e;
                // 1049 = database not found
                // 1045 = bad credentials
            }
        }
        return self::$database;
    }

    /**
     * Singletons should not be cloneable.
     *
     * @return void
     */
    protected function __clone()
    {
    }

    /**
     * Singletons should not be restorable from strings.
     *
     * @return void
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}
