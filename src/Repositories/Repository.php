<?php

namespace Application\Repositories;

use Application\Lib\DatabaseConnection;

class Repository
{

    public DatabaseConnection $connection;

    public function findAll(string $option = "", array $optionsData = [])
    {
        $statement = $this->getSelectStatementByModel($option ." ORDER BY created_at DESC", $optionsData);
        $entities = [];
        while (($row = $statement->fetch())) {
            $entity = $this->createEntity($row);
            $entities[] = $entity;
        }
        return $entities;
    }

    public function findOne($identifier)
    {
        $statement = $this->getSelectStatementByModel("WHERE id = ?", [$identifier]);
        $row = $statement->fetch();
        $entity = $this->createEntity($row);
        return $entity;
    }

    public function add($entity): bool
    {
        $sql = "";
        $values = [];
        $sql .= "INSERT INTO " . $entity::TABLE . " (";
        foreach ($entity as $key => $value) {
            if ($key !== "passwordConfirm") {
                $values[] = $value=="" ? "NULL" : $value;
                $sql .= $key . ", ";
            }
        }
        $sql .= "created_at) VALUES (";
        foreach ($entity as  $key => $val) {
            if ($key !== "passwordConfirm") {
                $sql .= "?, ";
            }
        }
        $sql .= "NOW())";
        $statement = $this->connection->getConnection()->prepare($sql);
        $affectedLines = $statement->execute($values);
        return ($affectedLines > 0);
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
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE id = ?";
        $values[] = $identifier;
        $statement = $this->connection->getConnection()->prepare($sql);
        $affectedLines = $statement->execute($values);
        return ($affectedLines > 0);
    }

    public function delete($identifier): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'DELETE FROM ' . $this->model::TABLE . ' WHERE id=?'
        );
        $affectedLines = $statement->execute([$identifier]);
        return ($affectedLines > 0);
    }

    public function getModel()
    {
        return get_class_vars(get_class($this->model));
    }

    public function getSelectStatementByModel(string $options, array $optionsData = [])
    {
        $sql = "SELECT ";
        foreach ($this->getModel() as $key => $value) {
            $sql .= $key . ", ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= " FROM " . $this->model::TABLE . " " . $options;
        $statement = $this->connection->getConnection()->prepare($sql);
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
                $entity->username = $entity->first . ' ' . $entity->last;
                $entity->initials = substr($entity->first,0,1).substr($entity->last,0,1);
            } else {
                $entity->initials = $entity->username;
            }
        }
        return $entity;
    }
}
