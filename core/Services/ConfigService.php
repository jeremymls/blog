<?php

namespace Core\Services;

use Core\Middleware\Superglobals;
use Core\Middleware\TemplateRenderer;
use Core\Models\Config;
use Core\Repositories\ConfigRepository;

class ConfigService extends EntityService
{
    protected $configRepository;
    protected $model;
    protected $configs;

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

    public function getSortedParameters($prefix)
    {
        $params = $this->getAll("where name like ?", [$prefix . '_%'], "", 'name', 'ASC');
        switch ($prefix) {
            case 'cs':
                $params['title'] = 'du site';
                break;
            case 'mb':
                $params['title'] = 'du mail';
                break;
            case 'rs':
                $params['title'] = 'des réseaux sociaux';
                break;
            case 'af':
                $params['title'] = 'de l\'affichage front';
                break;
            case 'sd':
                $params['title'] = 'des applications tierces';
                break;
            default:
                $params['title'] = '';
                break;
        }
        $params['prefix'] = $prefix;
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
                    "description" => $config[1],
                    "type" => isset($config[2]) ? $config[2] : "text",
                    "default_value" => $config[0],
                    "csrf_token" => $this->superglobals->getPost('csrf_token'),
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
        $models_files = array_diff($models_files, array('.', '..', 'Model.php', 'Error.php', 'MailContactModel.php'));
        $models = [];
        foreach($models_files as $model_file){
            $str=strtolower(str_replace(".php", "", $model_file));
            $models[] = (substr($str, -1) == "y") ? (substr($str,0, -1) . "ies"): $str . "s";
        }
        $tables = $this->configRepository->getTables();
        $missing_tables = array_diff($models, $tables);
        return $missing_tables;
    }

    public function create_config_table($table)
    {
        $this->configRepository->create_config_table($table);
    }

    private function getConfigsObject()
    {
        $configs = $this->getAll()['configs'];
        $configs_object = [];
        foreach ($configs as $config) {
            $configs_object[$config->name] = $config;
        }
        return $configs_object;
    }

    public function delete_value($id)
    {
        $entity = new Config;
        $entity->value = null;
        $entity->csrf_token = $this->superglobals->getPost('csrf_token');
        $success = $this->repository->update($id, $entity);
        if (!$success) {
            throw new \Exception("Impossible de supprimer cette valeur.");
        }
        $this->flashServices->success(
            'Configuration modifiée',
            'La valeur a bien été supprimée.'
        );
    }

    public function renderColor($twig)
    {
        $params = $this->getAll("where name LIKE 'af_color%'");
        $renderer = new TemplateRenderer($twig, $_SERVER['DOCUMENT_ROOT'], 'assets/styles.css.twig'); // todo: à revoir 
        foreach ($params['configs'] as $param) {
            $renderer->setParam($param->name, $param->value);
        }
        $renderer->render();
    }

    public static function change_env($environment)
    {
        if (!in_array($environment, ['DEV', 'PROD', 'TEST'])) {
            throw new \Exception("L'environnement $environment n'existe pas.");
        }
        if ($environment == Superglobals::getInstance()->getAppEnv()) {
            throw new \Exception("L'environnement est déjà $environment");
        }
        $dotEnv = file_get_contents("./.env");
        $output = preg_replace("/APP_ENV=(.*)/", "APP_ENV=$environment", $dotEnv);
        file_put_contents("./.env", $output);
        Superglobals::getInstance()->setAppEnv($environment);
    }
}
