<?php

namespace Core\Services;

use Core\Models\Param;
use Core\Repositories\ParamRepository;

class ParamService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->paramRepository = new ParamRepository();
        $this->model = new Param();
    }

    public function getParams()
    {
        $params = $this->paramRepository->findAll();
        return $params;
    }

    public function getParam($id)
    {
        $param = $this->paramRepository->findOne($id);
        return $param;
    }

    public function getMailContact()
    {
        $params = $this->getParams();
        return ['name' => $params['site_name'], 'email' => $params['owner_email']];
    }

    public function getMailConfig()
    {
        $params = $this->getParams();
        return [
            'host' => $params['mb_host'],
            'user' => $params['mb_user'],
            'pass' => $params['mb_pass'],
            'name' => $params['mb_name']
        ];
    }

    public function get($name)
    {
        $param = $this->paramRepository->findOneBy('name', $name);
        if ($param->name == "") {
            echo "<script>alert(\"Le paramètre ". $name ." n'existe pas. Réinitialiser vos paramètres\")</script>";
            //    $this->flashServices->danger("Paramètre introuvable", "Le paramètre ". $name ." n'existe pas");
        }
        return $param->value;
    }

    public function update($id, $value)
    {
        $this->paramRepository->update($id, $value);
    }

    public function initParams()
    {
        include_once ROOT . '/src/config/default.php';
        $params = $this->getParams();
        foreach ($params as $param) {
            $list[] = $param->name;
        }
        foreach (PARAMS as $key => $param) {
            if (!in_array($key, $list)) {
                $input = $this->validateForm([
                    "name" => $key,
                    "value" => $param[0],
                    "description" => $param[1]
                ]);
                $this->paramRepository->add($input);
            }
        }
    }

    public function compareParams()
    {
        include_once ROOT . '/src/config/default.php';
        $params = $this->getParams();
        foreach ($params as $param) {
            $list[] = $param->name;
        }
        $missing_params= [];
        foreach (PARAMS as $key => $param) {
            if (!in_array($key, $list)) {
                $missing_params[] = $key;
            }
        }
        return $missing_params;
    }
}
