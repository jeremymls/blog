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

use Core\Lib\Connection;
use Core\Middleware\Session\PHPSession;
use Core\Middleware\Superglobals;
use Core\Models\Token;
use Core\Services\CsrfService;
use Core\Services\Encryption;

/**
 * Repository base class
 *
 * @category Core
 * @package  Core\Repositories
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Repository
{
    protected $superglobals;
    protected $connection;
    protected $model;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->superglobals = Superglobals::getInstance();
        $this->connection = Connection::getConnection();
    }

    /**
     * Check CSRF
     *
     * Check the CSRF token
     *
     * @param mixed $csrf_token CSRF token
     *
     * @return void
     */
    private function checkCrsf($csrf_token)
    {
        $csrfService = CsrfService::getInstance();
        if (!$csrfService->checkToken($csrf_token)) {
            throw new \Exception('Le token CSRF est invalide ou n\'existe pas');
        }
    }

    /**
     * Add
     *
     * Add an entity to the database
     *
     * @param mixed $entity Entity
     *
     * @return bool
     */
    public function add(object $entity): bool
    {
        if (get_class($entity) != Token::class) {
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
            $values[] = $value == "" ? null : $value;
            $sql .= $key . ", ";
        }
        $sql .= "created_at) VALUES (";
        if ($this->superglobals->isExistPicture($entity::TABLE)) {
            $sql .= "?, ";
        }
        foreach ($entity as $key => $val) {
            $sql .= "?, ";
        }
        $sql .= "NOW())";
        $statement = $this->connection->prepare($sql);
        $affectedLines = $statement->execute($values);
        return ($affectedLines > 0);
    }

    /**
     * Find One
     *
     * Find one entity by its identifier
     *
     * @param mixed $identifier ID
     *
     * @return mixed Entity
     */
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

    /**
     * Find By
     *
     * Find one entity by a specific option
     *
     * @param string $option     The option to use
     * @param array  $optionData The data to use with the option
     *
     * @return mixed Array of entities
     */
    public function findBy(string $option, array $optionData = [])
    {
        $option = "WHERE $option";
        $statement = $this->getSelectStatementByModel(
            $option,
            $optionData
        );
        $row = $statement->fetch();
        $entity = $this->createEntity($row);
        return $entity;
    }

    /**
     * Find All
     *
     * Find all entities
     *
     * Can be filtered by an option and ordered by a specific column
     *
     * @param string $option      The option to use
     * @param array  $optionsData The data to use with the option
     * @param string $limit       The limit to use
     * @param string $order       The column to order by
     * @param string $direction   The direction to order by
     *
     * @return mixed The entities
     */
    public function findAll(
        string $option = "",
        array $optionsData = [],
        string $limit = "",
        string $order = null,
        $direction = "DESC"
    ) {
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

    /**
     * Update
     *
     * Update an entity
     *
     * @param mixed $identifier The identifier of the entity
     * @param mixed $entity     The entity
     *
     * @return bool
     */
    public function update(string $identifier, object $entity): bool
    {
        $this->checkCrsf($entity->csrf_token);
        $this->removeObsoleteProperties($entity);
        $values = [];
        $sql = str_replace(":table", $this->model::TABLE, "UPDATE :table SET ");
        foreach ($entity as $key => $value) {
            $values[] = $value;
            $sql .= $key . " = ?, ";
        }
        // if (count($_FILES) > 0 && isset($_FILES['picture']['error'])
        // && $_FILES['picture']['error'] !== 4) {
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

    /**
     * Delete
     *
     * Delete an entity
     *
     * @param mixed $identifier The identifier of the entity
     *
     * @return bool
     */
    public function delete($identifier): bool
    {
        $this->checkCrsf($this->superglobals->getPost('csrf_token'));
        $sql = "DELETE FROM :table WHERE id = ?";
        $sql = str_replace(":table", $this->model::TABLE, $sql);
        $statement = $this->connection->prepare($sql);
        $affectedLines = $statement->execute([$identifier]);
        $affectedLines = $statement->rowCount();
        return ($affectedLines > 0);
    }

    /**
     * Get Model
     *
     * Get the model of the entity
     *
     * @return mixed
     */
    public function getModel()
    {
        return get_class_vars(get_class($this->model));
    }

    /**
     * Get Select Statement By Model
     *
     * Get a select statement by a model
     *
     * @param string $options     The options to use
     * @param array  $optionsData The options data
     *
     * @return mixed
     */
    public function getSelectStatementByModel(
        string $options,
        array $optionsData = []
    ) {
        $this->checkTableExistence();
        $sql = "SELECT ";
        foreach ($this->getModel() as $key => $value) {
            $sql .= $key . ", ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= " FROM :table $options";
        $sql = str_replace(":table", $this->model::TABLE, $sql);
        $statement = $this->connection->prepare($sql);
        $statement->execute($optionsData);
        return $statement;
    }

    /**
     * Create Entity
     *
     * Create an entity from a row of a database
     *
     * @param mixed $row The row of the database
     *
     * @return object
     */
    public function createEntity($row): object
    {
        $entity = new $this->model();
        foreach ($this->getModel() as $key => $value) {
            if (!isset($row[$key]) && $key != "id" && $key != "created_at") {
                $entity->$key = false;
            }
            if (isset($row[$key])) {
                if ($key == "id") {
                    $entity->setId($row[$key]);
                    $entity->identifier = $row[$key];
                } elseif ($key == "created_at") {
                    $entity->setCreatedAt($row[$key]);
                    $entity->frenchCreationDate = $entity->getFrenchCreationDate();
                } elseif (
                    $key == "value"
                    && isset($row["name"])
                    && (substr($row["name"], 0, 3) == "mb_"
                    || substr($row["name"], 0, 3) == "sd_")
                ) {
                    // decrypt mail config
                    $entity->$key = $row[$key]
                    ? Encryption::decrypt($row[$key])
                    : "";
                } else {
                    $entity->$key = $row[$key] ? $row[$key] : "";
                }
            }
        }
        // USERNAME
        if (
            isset($entity->username)
            || isset($entity->last)
            || isset($entity->first)
        ) {
            if ($entity->username == "" || $entity->username == 'NULL') {
                $entity->isUsername = false;
                $entity->username = $entity->first . ' ' . $entity->last;
                $entity->initials = substr($entity->first, 0, 1)
                . substr($entity->last, 0, 1);
            } else {
                $entity->isUsername = true;
                $entity->initials = $entity->username;
            }
        }
        // Check if the entity has links
        if (in_array("getLinks", get_class_methods($entity))) {
            foreach ($entity->getLinks() as $field => $repository) {
                $entity->with($field, "Application\\Repositories\\" . $repository);
            }
        }
        return $entity;
    }

    /**
     * Remove Obsolete Properties
     *
     * Remove obsolete properties from an entity
     *
     * @param mixed $entity The entity to clean
     *
     * @return mixed The cleaned entity
     */
    private function removeObsoleteProperties(object $entity)
    {
        foreach ($entity as $key => $value) {
            if ($key == "password") {
                $entity->setPassword($value);
            }
            if (!array_key_exists($key, $this->getModel())) {
                unset($entity->$key);
            }
        }
        return $entity;
    }

    /**
     * Get Picture
     *
     * Get a picture from a form
     *
     * @param string $key The key of the picture
     *
     * @return string The base64 picture
     */
    public function getPicture($key = "picture")
    {
        $base64 = "";
        $file_exts = array('gif', 'jpeg', 'png', 'webp');
        $picture = $this->superglobals->getPicture($key);
        $file_ext = strtolower(
            substr($picture['type'], strpos($picture['type'], '/') + 1)
        );
        $file_size = $picture['size'];
        $file_temp = $picture['tmp_name'];
        $file_max_size = 512000;
        $errors = "";
        if (!in_array($file_ext, $file_exts)) {
            $errors .= 'Extension du fichier non autorisée : '
            . implode(',', $file_exts) . "<br>";
        }
        if ($file_size > (int) $file_max_size || $file_size === 0) {
            $errors .= 'Fichier trop lourd : '
            . ($file_max_size / 1024)
            . ' Ko maximum';
        }
        if (empty($errors)) {
            $bin = file_get_contents($file_temp);
            $base64 = 'data:image/' . $file_ext
            . ';base64,' .   base64_encode($bin);
        } else {
            throw new \Exception($errors);
        }
        return $base64;
    }

    /**
     * Check Table Existence
     *
     * Check if the table exists
     *
     * @return void
     */
    private function checkTableExistence()
    {
            $sql = "SELECT *
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute(
                [
                    $this->superglobals->getDatabase()['name'],
                    $this->model::TABLE
                ]
            );
            $row = $statement->fetch();
        if (!$row) {
            PHPSession::getInstance()->set("safe_mode", true);
            $this->superglobals->redirect('init');
        }
    }
}
