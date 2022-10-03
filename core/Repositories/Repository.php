<?php

namespace Core\Repositories;

use Core\Lib\Singleton;

class Repository
{
    public function __construct()
    {
    }

    public function add($entity): bool
    {
        $sql = "";
        $values = [];
        $sql .= "INSERT INTO " . $entity::TABLE . " (";
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE && $entity::TABLE != "tokens") {
            $base64 = $this->getPicture($sql, $values);
            $values[] = $base64;
            $sql .= "picture, ";
        }
        foreach ($entity as $key => $value) {
            if ($key !== "passwordConfirm") {
                $values[] = $value=="" ? null : $value;
                $sql .= $key . ", ";
            }
        }
        $sql .= "created_at) VALUES (";
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE && $entity::TABLE != "tokens") {
            $sql .= "?, ";
        }
        foreach ($entity as  $key => $val) {
            if ($key !== "passwordConfirm") {
                $sql .= "?, ";
            }
        }
        $sql .= "NOW())";
        $statement = Singleton::getConnection()->prepare($sql);
        $affectedLines = $statement->execute($values);
        return ($affectedLines > 0);
    }

    public function findOne($identifier)
    {
        $statement = $this->getSelectStatementByModel("WHERE id = ?", [$identifier]);
        $row = $statement->fetch();
        if (!$row) {
            return null;
        }
        $entity = $this->createEntity($row);
        return $entity;
    }

    public function findBy(string $option, array $optionData=[])
    {
        $statement = $this->getSelectStatementByModel("WHERE " . $option, $optionData);
        $row = $statement->fetch();
        $entity = $this->createEntity($row);
        return $entity;
    }

    public function findAll(string $option = "", array $optionsData = [], string $limit = "")
    {
        $statement = $this->getSelectStatementByModel($option ." ORDER BY created_at DESC ". $limit, $optionsData);
        $entities = [];
        while (($row = $statement->fetch())) {
            $entity = $this->createEntity($row);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function update($identifier, $entity): bool
    {
        $values = [];
        $sql = "UPDATE " . $this->model::TABLE . " SET ";
        foreach ($entity as $key => $value) {
            if ($key !== "passwordConfirm") {
                $values[] = $value;
                $sql .= $key . " = ?, ";
            }
        }
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE && $entity::TABLE != "tokens") {
            $base64 = $this->getPicture($sql, $values);
            $values[] = $base64;
            $sql .= "picture = ?, ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE id = ?";
        $values[] = $identifier;
        $statement = Singleton::getConnection()->prepare($sql);
        $affectedLines = $statement->execute($values);
        return ($affectedLines > 0);
    }

    public function delete($identifier): bool
    {
        $statement = Singleton::getConnection()->prepare(
            'DELETE FROM ' . $this->model::TABLE . ' WHERE id=?'
        );
        $affectedLines = $statement->execute([$identifier]);
        $affectedLines = $statement->rowCount();
        return ($affectedLines > 0);
    }

    public function getModel()
    {
        return get_class_vars(get_class($this->model));
    }

    public function getSelectStatementByModel(string $options, array $optionsData = [])
    {
        if ($this->checkTableExistence() == false) {
            $_SESSION['safe_mode'] = true;
            header('Location: /init');
        }
        $sql = "SELECT ";
        foreach ($this->getModel() as $key => $value) {
            $sql .= $key . ", ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= " FROM " . $this->model::TABLE . " " . $options;
        $statement = Singleton::getConnection()->prepare($sql);
        $statement->execute($optionsData);
        return $statement;
    }

    /**
     * It creates an entity from a row of a database
     *
     * @param  mixed $row data
     * @return object An object of the class that is being called.
     */
    public function createEntity($row): object
    {
        $entity = new $this->model();
        foreach ($this->getModel() as $key => $value) {
            if (!isset($row[$key]) && $key != "id" && $key != "created_at" ) {
                $entity->$key = false;
            }
            if (isset($row[$key])) {
                if ($key == "id") {
                    $entity->setId($row[$key]);
                    $entity->identifier = $row[$key];
                } elseif ($key == "created_at") {
                    $entity->setCreatedAt($row[$key]);
                    $entity->frenchCreationDate = $entity->getFrenchCreationDate();
                } else {
                    $entity->$key = $row[$key]? $row[$key] : "";
                }
            }
        }
        if (isset($entity->username) || isset($entity->last) || isset($entity->first)) {
            if ($entity->username == "" || $entity->username == 'NULL') {
                $entity->isUsername = false;
                $entity->username = $entity->first . ' ' . $entity->last;
                $entity->initials = substr($entity->first, 0, 1).substr($entity->last, 0, 1);
            } else {
                $entity->isUsername = true;
                $entity->initials = $entity->username;
            }
        }
        // Check if the entity has links
        if (in_array("getLinks",get_class_methods($entity))) {
            foreach ($entity->getLinks() as $field => $repository) {
                $entity->with($field, "Application\\Repositories\\" . $repository);
            }
        }
        return $entity;
    }

    public function getPicture()
    {
        $base64="";
        $file_exts = array('gif', 'jpeg', 'png', 'webp');
        $file_ext = strtolower(substr($_FILES['picture']['type'],  strpos($_FILES['picture']['type'], '/') + 1));
        $file_size = $_FILES['picture']['size'];
        $file_temp = $_FILES['picture']['tmp_name'];
        $file_max_size = 512000;
        $errors = "";
        if (!in_array($file_ext, $file_exts)) {
            $errors .= 'Extension du fichier non autorisée : ' . implode(',', $file_exts)."<br>";
        }
        if ($file_size > (int) $file_max_size || $file_size === 0) {
            $errors .= 'Fichier trop lourd : ' . ($file_max_size / 1024) . ' Ko maximum';
        }
        if (empty($errors)) {
            $bin = file_get_contents($file_temp);
            $base64 = 'data:image/' . $file_ext . ';base64,' .   base64_encode($bin);
        } else {
            throw new \Exception($errors);
        }
        return $base64;
    }

    private function checkTableExistence()
    {
        $sql = "SELECT * 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_SCHEMA = ?
        AND TABLE_NAME = ?";
        $statement = Singleton::getConnection()->prepare($sql);
        $statement->execute([DB_NAME, $this->model::TABLE]);
        $row = $statement->fetch();
        if($row) {
            return true;
        } else {
            return false;
        }
    }

    public function getTables()
    {
        $tables = [];
        $sql = "SELECT table_name FROM information_schema.tables WHERE table_schema = ?";
        $statement = Singleton::getConnection()->prepare($sql);
        $statement->execute([DB_NAME]);
        while (($row = $statement->fetch())) {
            $tables[] = $row['table_name'];
        }
        return $tables;
    }

    public function create_config_table($table)
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . $table . " (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value VARCHAR(255) DEFAULT NULL,
            description VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        $statement = Singleton::getConnection()->prepare($sql);
        $statement->execute();
    }

    public function create_tables()
    {
        if (file_exists("sql/blog.sql")) { 
            $sql = file_get_contents('sql/blog.sql');
            $statement = Singleton::getConnection()->prepare($sql);
            $statement->execute();
        }
    }
}
