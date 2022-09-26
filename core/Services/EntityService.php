<?php
namespace Core\Services;

use Application\Models\Post;
use Application\Repositories\PostRepository;

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
            throw new \Exception('Impossible de d\'ajouter '. $this->getFrenchName(true) .' !');
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

    public function getAll(string $option = "", array $optionsData = [], string $limit = "")
    {
        $params[$this->modelName."s"] = $this->getRepository()->findAll($option, $optionsData, $limit);
        return $params;
    }

    public function delete($identifier)
    {
        $success = $this->getRepository()->delete($identifier);
        if (!$success) {
            throw new \Exception('Impossible de supprimer ' . $this->getFrenchName() . " #$identifier!");
        } 
        $this->flashServices->danger(
            $this->getFrenchName(true,"N") . ' supprimé'. $this->getFrenchGenderTermination(),
            $this->getFrenchName(true) . ' a bien été supprimé'. $this->getFrenchGenderTermination()
        );
    }
}