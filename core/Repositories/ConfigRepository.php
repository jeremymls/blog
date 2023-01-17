<?php

namespace Core\Repositories;

use Core\Middleware\Superglobals;
use Core\Models\Config;

/**
 * ConfigRepository
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
     * create_config_table
     * 
     * Create the config table
     *
     * @param  string $table
     */
    public function create_config_table($table)
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
     * getTables
     * 
     * Get all tables from the database
     *
     * @return mixed
     */
    public function getTables()
    {
        $tables = [];
        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = ?";
        $statement = $this->connection->prepare($sql);
        $statement->execute([$this->dbName]);
        while (($row = $statement->fetch())) {
            $tables[] = $row['table_name'];
        }
        return $tables;
    }
}
