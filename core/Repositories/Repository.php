<?php

namespace Core\Repositories;

use Core\Lib\Singleton;
use Core\Middleware\Session\PHPSession;
use Core\Middleware\Superglobals;
use Core\Models\Token;
use Core\Services\CsrfService;
use Core\Services\Encryption;

class Repository
{
    protected $superglobals;
    protected $connection;
    protected $model;

    public function __construct()
    {
        $this->superglobals = Superglobals::getInstance();
        $this->connection = Singleton::getConnection();
    }

    private function checkCrsf($csrf_token)
    {
        $csrfService = CsrfService::getInstance();
        if (!$csrfService->checkToken($csrf_token)) {
            throw new \Exception('Le token CSRF est invalide ou n\'existe pas');
        }
    }

    public function add($entity): bool
    {
        if (get_class($entity)!= Token::class) {
            $this->checkCrsf($entity->csrf_token);
        }
        $this->removeObsoleteProperties($entity);
        $sql = "";
        $values = [];
        $sql .= "INSERT INTO " . $entity::TABLE . " (";
        if ($this->superglobals->isExistPicture($entity::TABLE)) {
            $base64 = $this->getPicture();
            $values[] = $base64;
            $sql .= "picture, ";
        }
        foreach ($entity as $key => $value) {
            $values[] = $value=="" ? null : $value;
            $sql .= $key . ", ";
        }
        $sql .= "created_at) VALUES (";
        if ($this->superglobals->isExistPicture($entity::TABLE)) {
            $sql .= "?, ";
        }
        foreach ($entity as  $key => $val) {
            $sql .= "?, ";
        }
        $sql .= "NOW())";
        $statement = $this->connection->prepare($sql);
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

    public function findAll(string $option = "", array $optionsData = [], string $limit = "", string $order = null, $direction = "DESC")
    {
        $statement = $this->getSelectStatementByModel(
            $option .
            " ORDER BY " . ($order ? $order : 'created_at') . " " .
            $direction . " " .
            $limit,
            $optionsData
        );
        $entities = [];
        while (($row = $statement->fetch())) {
            $entity = $this->createEntity($row);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function update($identifier, $entity): bool
    {
        $this->checkCrsf($entity->csrf_token);
        $this->removeObsoleteProperties($entity);
        $values = [];
        $sql = "UPDATE " . $this->model::TABLE . " SET ";
        foreach ($entity as $key => $value) {
            $values[] = $value;
            $sql .= $key . " = ?, ";
        }
        // if (count($_FILES) > 0 && isset($_FILES['picture']['error']) && $_FILES['picture']['error'] !== 4) {
        if ($this->superglobals->isExistPicture()) {
            $fileName = array_keys($_FILES)[0];
            $base64 = $this->getPicture($fileName);
            $values[] = $base64;
            $sql .=  $fileName . " = ?, ";
        }

        $sql = substr($sql, 0, -2);
        $sql .= " WHERE id = ?";
        $values[] = $identifier;
        $statement = $this->connection->prepare($sql);
        $affectedLines = $statement->execute($values);
        return ($affectedLines > 0);
    }

    public function delete($identifier): bool
    {
        $this->checkCrsf($this->superglobals->getGet('csrf_token'));
        $statement = $this->connection->prepare(
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
        $this->checkTableExistence();
        $sql = "SELECT ";
        foreach ($this->getModel() as $key => $value) {
            $sql .= $key . ", ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= " FROM " . $this->model::TABLE . " " . $options;
        $statement = $this->connection->prepare($sql);
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
                } elseif ($key == "value" && isset($row["name"]) && (substr($row["name"], 0, 3) == "mb_"|| substr($row["name"], 0, 3) == "sd_")) {
                    $entity->$key = $row[$key]? Encryption::decrypt($row[$key]) : ""; // decrypt mail config
                } else {
                    $entity->$key = $row[$key]? $row[$key] : "";
                }
            }
        }
        // USERNAME
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

    private function removeObsoleteProperties($entity)
    {
        foreach ($entity as $key => $value) {
            if (!array_key_exists($key, $this->getModel())) {
                unset($entity->$key);
            }
        }
        return $entity;
    }

    public function getPicture($key = "picture")
    {
        $base64="";
        $file_exts = array('gif', 'jpeg', 'png', 'webp');
        $picture = $this->superglobals->getPicture($key);
        $file_ext = strtolower(substr($picture['type'],  strpos($picture['type'], '/') + 1));
        $file_size = $picture['size'];
        $file_temp = $picture['tmp_name'];
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
        if (!PHPSession::getInstance()->get("is_table")){
            $sql = "SELECT * 
            FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([$this->superglobals->getDatabase()['name'], $this->model::TABLE]);
            $row = $statement->fetch();
            if($row) {
                PHPSession::getInstance()->set("is_table", true);
            } else {
                PHPSession::getInstance()->set("safe_mode", true);
                $this->superglobals->redirect('init');
            } 
        }
    }
}
