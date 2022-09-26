<?php

namespace Core\Controllers;

use Core\Services\FlashService;
use Core\Services\ConfigService;

class ConfigController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->configService = new ConfigService();
        $this->flashService = new FlashService();
    }

    public function init_configs()
    {
        $this->configService->create_config_table('configs');
        $this->configService->initConfigs();
        $this->flashService->success("Configuration initialisée", "La configuration a été initialisée avec succès");
        header('Location: /');
    }

    public function init_missing_configs()
    {
        $this->configService->initConfigs();
        $this->flashService->success("Configuration initialisée", "La configuration a été initialisée avec succès");
        header('Location: /');
    }

    public function init_tables()
    {
        $this->configService->create_tables();
        header('Location: /');
    }

    public function index()
    {
        $params = $this->configService->getSortedParameters();
        $this->twig->display('admin/config/index.twig', $params);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->configService->update($id, $_POST);
            header('Location: /admin/configs');
        }
        $params = $this->configService->get($id);
        $this->twig->display('admin/config/edit.twig', $params);
    }
}
