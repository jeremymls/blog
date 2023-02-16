<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Services;

use Core\Middleware\Superglobals;
use Core\Middleware\TemplateRenderer;
use Core\Models\Config;
use Core\Repositories\ConfigRepository;

/**
 * ConfigService
 *
 * Service for Config
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class ConfigService extends EntityService
{
    protected $configRepository;
    protected $model;
    protected $configs;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->configRepository = new ConfigRepository();
        $this->model = new Config();
    }

    /**
     * Init
     *
     * Check if
     * * the database is initialized
     * * the configs are initialized
     *
     * @return array Array of missing tables and configs
     */
    public function init()
    {
        $params['missing_tables'] = $this->checkMissingTables();
        if (in_array('configs', $params['missing_tables'])) {
            $params['missing_configs'] = $this->getDefaultsConfigs();
        } else {
            $params['missing_configs'] = $this->checkMissingConfigs();
        }
        return $params;
    }

    /**
     * Get Owner Mail Contact
     *
     * Get the owner mail contact
     *
     * @return array Array of owner name and email
     */
    public function getOwnerMailContact()
    {
        $configs = $this->getConfigsObject();
        return [
            'name' => $configs['cs_owner_name']->value,
            'email' => $configs['cs_owner_email']->value];
    }

    /**
     * Get Mail Config
     *
     * Get the mail config
     *
     * @return array Array of mail config
     */
    public function getMailConfig()
    {
        $configs = $this->getConfigsObject();
        return [
            'Host' => $configs['mb_host']->value,
            'Username' => $configs['mb_user']->value,
            'Password' => $configs['mb_pass']->value,
        ];
    }

    /**
     * Get By Name
     *
     * Get a config by name
     *
     * @param string $name Name of the config
     *
     * @return string Value of the config
     */
    public function getByName($name)
    {
        $config = $this->configRepository->findBy('name = ?', [$name]);
        if ($config === null) {
            throw new \Exception("Configuration $name n'existe pas.");
        }
        return $config->value;
    }

    /**
     * Get Sorted Parameters
     *
     * Get the parameters sorted by prefix and add the title
     *
     * @param mixed $prefix Prefix of the parameters
     *
     * @return array Array of parameters
     */
    public function getSortedParameters($prefix)
    {
        $params = $this->getAll(
            "where name like ?",
            [$prefix . '_%'],
            "",
            'name',
            'ASC'
        );
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

    /**
     * Init Configs
     *
     * Init missing configs
     *
     * @return void
     */
    public function initConfigs()
    {
        include_once ROOT . '/src/config/default.php';
        // $configs = $this->getAll()['configs'];
        $list = [];
        $configs = $this->getConfigsObject();
        foreach ($configs as $config) {
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

    /**
     * Get Defaults Configs
     *
     * Get the default configs
     *
     * @return array Array of default configs
     */
    public function getDefaultsConfigs()
    {
        include_once ROOT . '/src/config/default.php';
        foreach (CONFIGS as $key => $config) {
            $defaults_configs[] = $key;
        }
        return $defaults_configs;
    }

    /**
     * Check Missing Configs
     *
     * Check missing configs
     *
     * @return array Array of missing configs
     */
    public function checkMissingConfigs()
    {
        include_once ROOT . '/src/config/default.php';
        $list = [];
        $configs = $this->getConfigsObject();
        foreach ($configs as $config) {
            $list[] = $config->name;
        }
        return array_diff($this->getDefaultsConfigs(), $list);
    }

    /**
     * Check Missing Tables
     *
     * Check missing tables
     *
     * @return array Array of missing tables
     */
    public function checkMissingTables()
    {
        $models_files = scandir("./src/Models");
        $models_files = array_merge($models_files, scandir("./core/Models"));
        $models_files = array_diff(
            $models_files,
            array('.', '..', 'Model.php', 'Error.php', 'MailContactModel.php')
        );
        $models = [];
        foreach ($models_files as $model_file) {
            $str = strtolower(str_replace(".php", "", $model_file));
            $models[] = (substr($str, -1) == "y")
            ? (substr($str, 0, -1)
            . "ies") : $str . "s";
        }
        $tables = $this->configRepository->getTables();
        $missing_tables = array_diff($models, $tables);
        return $missing_tables;
    }

    /**
     * Create Config Table
     *
     * Create a config table
     *
     * @param string $table Name of the table
     *
     * @return void
     */
    public function createConfigTable($table)
    {
        $this->configRepository->createConfigTable($table);
    }

    /**
     * Get Configs Object
     *
     * Get the configs as an object
     *
     * @return array Array of configs
     */
    private function getConfigsObject()
    {
        $configs = $this->getAll()['configs'];
        $configs_object = [];
        foreach ($configs as $config) {
            $configs_object[$config->name] = $config;
        }
        return $configs_object;
    }

    /**
     * Delete Value
     *
     * Delete a value
     *
     * @param mixed $id Id of the config
     *
     * @return void
     */
    public function deleteValue($id)
    {
        $entity = new Config();
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

    /**
     * Render Color
     *
     * Render the color CSS file
     *
     * @param mixed $twig Twig instance
     *
     * @return void
     */
    public function renderColor($twig)
    {
        $params = $this->getAll("where name LIKE 'af_color%'");
        $renderer = new TemplateRenderer(
            $twig,
            $this->superglobals->getServer('DOCUMENT_ROOT'),
            'assets/styles.css.twig'
        ); // todo: à revoir pour la doc
        foreach ($params['configs'] as $param) {
            $renderer->setParam($param->name, $param->value);
        }
        $renderer->render();
    }

    /**
     * Change ENV
     *
     * Change the environment
     *
     * @param string $environment Environment
     *
     * @return void
     */
    public static function changeEnv($environment)
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
