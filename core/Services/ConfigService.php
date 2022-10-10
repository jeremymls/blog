<?php

namespace Core\Services;

use Core\Models\Config;
use Core\Repositories\ConfigRepository;

class ConfigService extends EntityService
{
    public function __construct()
    {
        parent::__construct();
        $this->configRepository = new ConfigRepository();
        $this->model = new Config();
        $this->configs = $this->getConfigsObject();
    }

    public function init()
    {
        $params['missing_tables'] = $this->checkMissingTables();
        if (in_array('configs', $params['missing_tables']) ) {
            $params['missing_configs'] = $this->getDefaultsConfigs();
        } else {
            $params['missing_configs'] = $this->checkMissingConfigs();
        }
        return $params;
    }

    public function getOwnerMailContact()
    {
        return [
            'name' => $this->configs['cs_owner_name']->value, 
            'email' => $this->configs['cs_owner_email']->value];
    }

    public function getMailConfig()
    {
        return [
            'Host' => $this->configs['mb_host']->value,
            'Username' => $this->configs['mb_user']->value,
            'Password' => $this->configs['mb_pass']->value,
        ];
    }

    public function getByName($name)
    {
        $config = $this->configRepository->findBy('name = ?', [$name]);
        if ($config === null) {
            throw new \Exception("Configuration $name n'existe pas.");
        }
        return $config->value;
    }

    public function getSortedParameters()
    {
        $params['missing_configs'] = $this->checkMissingConfigs();
        $params['configs'] = $this->sortConfigs((array) $this->getAll()['configs']);
        return $params;
    }

    public function initConfigs()
    {
        include_once ROOT . '/src/config/default.php';
        // $configs = $this->getAll()['configs'];
        $list = [];
        foreach ($this->configs as $config) {
            $list[] = $config->name;
        }
        foreach (CONFIGS as $key => $config) {
            if (!in_array($key, $list)) {
                $input = $this->validateForm(
                    [
                    "name" => $key,
                    "value" => $config[0],
                    "description" => $config[1]
                    ]
                );
                $this->repository->add($input);
            }
        }
    }

    public function getDefaultsConfigs()
    {
        include_once ROOT . '/src/config/default.php';
        foreach (CONFIGS as $key => $config) {
            $defaults_configs[] = $key;
        }
        return $defaults_configs;
    }

    public function checkMissingConfigs()
    {
        include_once ROOT . '/src/config/default.php';
        $list = [];
        foreach ($this->configs as $config) {
            $list[] = $config->name;
        }
        return array_diff($this->getDefaultsConfigs(), $list);
    }

    public function checkMissingTables()
    {
        $models_files = scandir("./src/Models");
        $models_files = array_merge($models_files, scandir("./core/Models"));
        $models_files = array_diff($models_files, array('.', '..', 'Model.php', 'Error.php'));
        $models = [];
        foreach($models_files as $model_file){
            $models[] = strtolower(str_replace(".php", "", $model_file))."s";
        }
        $tables = $this->configRepository->getTables();
        $missing_tables = array_diff($models, $tables);
        return $missing_tables;
    }

    public function create_tables()
    {
        $this->configRepository->create_tables();
    }

    public function create_config_table($table)
    {
        $this->configRepository->create_config_table($table);
    }

    private function sortConfigs(array $configs)
    {
        foreach ($configs as $config) {
            $prefix = explode('_', $config->name)[0];
            switch ($prefix) {
            case 'cs':
                $prefix = 'Site';
                break;
            case 'mb':
                $prefix = 'Configuration mail';
                break;
            case 'rs':
                $prefix = 'Réseaux sociaux';
                break;
            default:
                $other_configs[] = $config;
                break;
            }
            $sort_configs[$prefix][] = $config;
        }
        return $sort_configs;
    }

    private function getConfigsObject()
    {
        $configs = $this->getAll()['configs'];
        foreach ($configs as $config) {
            $configs_object[$config->name] = $config;
        }
        return $configs_object;
    }
}
