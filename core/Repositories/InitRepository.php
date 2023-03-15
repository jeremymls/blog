<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Repositories
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Repositories;

use Core\Middleware\Superglobals;

/**
 * InitRepository
 *
 * Manage the database creation
 *
 * @category Core
 * @package  Core\Repositories\ConfigRepository
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
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
     * Create Database
     *
     * Create the database
     *
     * @return void
     */
    public static function createDatabase()
    {
        $superglobals = Superglobals::getInstance();
        $dbData = $superglobals->getDatabase();
        $bdd = new \PDO(
            'mysql:host=' . $dbData['host'] . ';dbname=mysql;charset=utf8',
            $dbData['user'],
            $dbData['pass']
        );
        $sql = "CREATE DATABASE IF NOT EXISTS " . $dbData['name'];
        $statement = $bdd->prepare($sql);
        $statement->execute();
    }

    /**
     * Delete Database
     *
     * Delete the database
     *
     * @return void
     */
    public function deleteDatabase()
    {
        $superglobals = Superglobals::getInstance();
        $dbData = $superglobals->getDatabase();
        $bdd = new \PDO(
            'mysql:host=' . $dbData['host'] . ';dbname=mysql;charset=utf8',
            $dbData['user'],
            $dbData['pass']
        );
        $sql = "DROP DATABASE IF EXISTS " . $dbData['name'];
        $statement = $bdd->prepare($sql);
        $statement->execute();
        if ($statement->rowCount() == 0) {
            throw new \Exception();
        }
    }
}
