<?php
namespace Core\Services;

use stdClass;

/**
 * EntityService
 * 
 * CRUD operations on entities
 */
class EntityService extends Service
{
    protected $modelName;
    protected $repository;
    protected $model;
    protected $flashServices;
    protected $superglobals;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelName = $this->getModelName();
        $this->repository = $this->getRepository();
    }

    /**
     * add
     * 
     * Add an entity to the database
     *
     * @param  mixed $input The data to add
     * @param  array $options
     * @param  mixed $flashMsg The message to display in the flash
     */
    public function add($input, $options=[],$flashMsg = null)
    {
        if (count($options) > 0) {
            $input = array_merge($input, $options);
        }
        $entity = $this->validateForm($input, $this->model->getFillable());
        $success = $this->repository->add($entity);
        if (!$success) {
            throw new \Exception('Impossible de d\'ajouter '. $this->getFrenchName() .' !');
        } 
        $this->flashServices->success(
            $this->getFrenchName(true, "N") .' ajouté'. $this->getFrenchGenderTermination(),
            ($flashMsg ?? $this->getFrenchName(true) .' a bien été ajouté'. $this->getFrenchGenderTermination())
        );
    }

    /**
     * get
     * 
     * Get an entity from the database by its id
     *
     * @param  mixed $identifier
     * @return array
     */
    public function get($identifier)
    {
        $params[$this->modelName] = $this->getRepository()->findOne($identifier);
        if ($params[$this->modelName] === null) {
            throw new \Exception($this->getFrenchName(true) . " #$identifier n'existe pas.");
        }
        return $params;
    }

    /**
     * getBy
     * 
     * Get an entity from the database by a specific option
     *
     * @param  string $option
     * @param  array $optionData
     * @return array
     */
    public function getBy(string $option, array $optionData=[])
    {
        $params[$this->modelName] = $this->getRepository()->findBy($option, $optionData);
        if ($params[$this->modelName] === null) {
            throw new \Exception($this->getFrenchName(true) . " n'existe pas.");
        }
        return $params;
    }

    /**
     * getAll
     * 
     * Get all entities from the database
     * 
     * @param  string $option The option to filter the query
     * @param  array $optionsData The data to filter the query
     * @param  string $limit The limit of the query
     * @param  string $order The column to order by
     * @param  string $direction DESC or ASC
     * @return array
     */
    public function getAll(string $option = "", array $optionsData = [], string $limit = "", string $order = null, string $direction = "DESC")
    {
        $modelName = (substr($this->modelName, -1) === "y") ? (substr($this->modelName, 0, -1). "ies") : $this->modelName . "s";
        $params[$modelName] = $this->getRepository()->findAll($option, $optionsData, $limit, $order, $direction);
        return $params;
    }

    /**
     * update
     * 
     * Update an entity in the database
     * 
     * @param  mixed $identifier The id of the entity to update
     * @param  mixed $input The data to update
     * @param  string $flashMsg The message to display in the flash
     * @param  bool $modelValidation If the data should be validated by the model
     * @param  bool $showFlash If the flash should be displayed
     */
    public function update($identifier, $input, $flashMsg = "", $modelValidation= true, $showFlash = true)
    {
        $entity = $modelValidation ? $this->validateForm($input): $input;
        $success = $this->repository->update($identifier, $entity);
        if (!$success) {
            throw new \Exception('Impossible de modifier '. $this->getFrenchName() .' !');
        }
        if ($showFlash) {
            $this->flashServices->success(
                $this->getFrenchName(true, "N") .' modifié'. $this->getFrenchGenderTermination(),
                $flashMsg ?: $this->getFrenchName(true) ." #$identifier a bien été modifié". $this->getFrenchGenderTermination()
            );
        }
    }

    /**
     * delete_ajax
     * 
     * Soft delete an entity in the database
     *
     * @param  mixed $identifier
     * @param  string $delete The action to perform (delete or restore)
     * @return bool True if the operation was successful
     */
    public function delete_ajax($identifier, $delete)
    {
        $entity = new stdClass;
        if ($delete == "delete") {
            $entity->deleted = 1;
        } elseif ($delete == "restore") {
            $entity->deleted = 0;
            $entity->moderate = 0;
        }
        $entity->csrf_token = $this->superglobals->getGet("csrf_token");
        $this->repository->update($identifier, $entity);
        if ($delete == "delete") {
            $this->flashServices->danger(
                $this->getFrenchName(true, "N") .' supprimé'. $this->getFrenchGenderTermination(),
                $this->getFrenchName(true) ." #$identifier a bien été supprimé". $this->getFrenchGenderTermination()
            );
        } elseif ($delete == "restore") {
            $this->flashServices->success(
                $this->getFrenchName(true, "N") .' restauré'. $this->getFrenchGenderTermination(),
                $this->getFrenchName(true) ." #$identifier a bien été restauré et sera à nouveau soumis à modération"
            );
        }
        return true;
    }

    /**
     * delete
     * 
     * Delete an entity in the database
     *
     * @param  mixed $identifier
     */
    public function delete($identifier)
    {
        $success = $this->getRepository()->delete($identifier);
        if (!$success) {
            throw new \Exception('Impossible de supprimer ' . $this->getFrenchName() . " #$identifier!");
        } 
        $this->flashServices->danger(
            $this->getFrenchName(true,"N") . ' supprimé'. $this->getFrenchGenderTermination(),
            $this->getFrenchName(true) . " #$identifier a bien été supprimé". $this->getFrenchGenderTermination()
        );
    }
}