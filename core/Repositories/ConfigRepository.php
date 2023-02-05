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
use Core\Models\Config;

/**
 * ConfigRepository
 *
 * @category Core
 * @package  Core\Repositories
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class ConfigRepository extends Repository
{
    protected $dbName;
    protected $model;
    protected $connection;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Config();
        $this->dbName = Superglobals::getInstance()->getDatabase()['name'];
    }

    /**
     * Create Config Table
     *
     * Create the config table
     *
     * @param string $table Table name
     *
     * @return void
     */
    public function createConfigTable($table)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $table . " (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value VARCHAR(255) DEFAULT NULL,
            description VARCHAR(255) DEFAULT NULL,
            type VARCHAR(255) DEFAULT NULL,
            default_value VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    /**
     * Get Tables
     *
     * Get all tables from the database
     *
     * @return mixed
     */
    public function getTables()
    {
        $tables = [];
        $sql = "SELECT table_name
            FROM information_schema.tables
            WHERE table_schema = ?";
        $statement = $this->connection->prepare($sql);
        $statement->execute([$this->dbName]);
        while (($row = $statement->fetch())) {
            $tables[] = $row['table_name'];
        }
        return $tables;
    }
}
