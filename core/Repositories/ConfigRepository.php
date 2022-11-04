<?php

namespace Core\Repositories;

use Core\Middleware\Superglobals;
use Core\Models\Config;

class ConfigRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Config();
        $this->dbName = Superglobals::getInstance()->getDatabase()['name'];
    }

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
    
    public function create_tables()
    {
        if (file_exists("sql/blog.sql")) { 
            $sql = file_get_contents('sql/blog.sql');
            $statement = $this->connection->prepare($sql);
            $statement->execute();
        }
    }
}
