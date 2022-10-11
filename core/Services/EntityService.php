<?php
namespace Core\Services;

class EntityService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->modelName = $this->getModelName();
        $this->repository = $this->getRepository();
    }

    public function add($input, $options=[])
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
            $this->getFrenchName(true) .' a bien été ajouté'. $this->getFrenchGenderTermination()
        );
    }

    public function get($identifier)
    {
        $params[$this->modelName] = $this->getRepository()->findOne($identifier);
        if ($params[$this->modelName] === null) {
            throw new \Exception($this->getFrenchName(true) . " #$identifier n'existe pas.");
        }
        return $params;
    }

    public function getBy(string $option, array $optionData=[])
    {
        $params[$this->modelName] = $this->getRepository()->findBy($option, $optionData);
        if ($params[$this->modelName] === null) {
            throw new \Exception($this->getFrenchName(true) . " n'existe pas.");
        }
        return $params;
    }
    

    public function getAll(string $option = "", array $optionsData = [], string $limit = "", string $order = null, string $direction = "DESC")
    {
        $modelName = (substr($this->modelName, -1) === "y") ? (substr($this->modelName, 0, -1). "ies") : $this->modelName . "s";
        $params[$modelName] = $this->getRepository()->findAll($option, $optionsData, $limit, $order, $direction);
        return $params;
    }

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

    public function delete_ajax($identifier, $delete = true)
    {
        $delete = $delete ? 1 : 0;
        $this->repository->update($identifier, ['deleted' => $delete]);
        return true;
    }


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