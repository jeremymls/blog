<?php

namespace Core\Services;

use Core\Models\Config;
use Core\Repositories\ConfigRepository;

class ConfigService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->configRepository = new ConfigRepository();
        $this->model = new Config();
    }

    public function init()
    {
        $params['missing_tables'] = $this->checkMissingTables();
        if ( in_array('configs', $params['missing_tables']) ) {
            $params['missing_configs'] = $this->getDefaultsConfigs();
        } else {
            $params['missing_configs'] = $this->checkMissingConfigs();
        }
        return $params;
    }

    public function getConfigs()
    {
        $configs = $this->configRepository->findAll();
        return $configs;
    }

    public function getConfig($id)
    {
        $config = $this->configRepository->findOne($id);
        return $config;
    }

    public function getMailContact()
    {
        $configs = $this->getConfigs();
        return ['name' => $configs['cs_site_name'], 'email' => $configs['cs_owner_email']];
    }

    public function getMailConfig()
    {
        $configs = $this->getConfigs();
        return [
            'host' => $configs['mb_host'],
            'user' => $configs['mb_user'],
            'pass' => $configs['mb_pass'],
            'name' => $configs['mb_name']
        ];
    }

    public function get($name)
    {
        $config = $this->configRepository->findOneBy('name', $name);
        // if ($config->name == "") {
        //     header('Location: /init');
        // }
        return $config->value;
    }

    public function getSortedParameters()
    {
        $params['missing_configs'] = $this->checkMissingConfigs();
        $params['configs'] = $this->sortConfigs((array) $this->getConfigs());
        return $params;
    }

    public function update($id, $value)
    {
        $success = $this->configRepository->update($id, $value);
        if (!$success) {
            $this->flashService->error("Erreur", "Une erreur est survenue lors de la mise à jour de la configuration");
        }
    }

    public function initConfigs()
    {
        include_once ROOT . '/src/config/default.php';
        $configs = $this->getConfigs();
        $list = [];
        foreach ($configs as $config) {
            $list[] = $config->name;
        }
        foreach (CONFIGS as $key => $config) {
            if (!in_array($key, $list)) {
                $input = $this->validateForm([
                    "name" => $key,
                    "value" => $config[0],
                    "description" => $config[1]
                ]);
                $this->configRepository->add($input);
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
        $configs = $this->getConfigs();
        $list = [];
        foreach ($configs as $config) {
            $list[] = $config->name;
        }
        return array_diff($this->getDefaultsConfigs(),$list);
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
}
