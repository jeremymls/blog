<?php

namespace Core\Middleware;

use Core\Model;

class ValidateForm
{
    public function __construct(string $model, array $input, array $requiredFields = [])
    {
        $conditions = [];
        $model = "Application\\Models\\" . $model;
        $this->model = new $model();
        
        foreach ($requiredFields as $key => $field) {
            $conditions[] = !empty($input[$field])?'ok':'ko';
        }
        if (!in_array("ko", $conditions) ){
            foreach ($input as $key => $value) {
                $this->model->$key = $value;
            } 
        $this->model;
        }else {
                throw new \Exception('Les données du formulaire sont invalides.');
        }
    }
}