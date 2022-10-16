<?php

namespace Core\Controllers;

use Core\Services\FlashService;
use Core\Services\ConfigService;
use Core\Services\Encryption;

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
        $this->twig->display('admin/config/index.twig');
    }

    public function list($prefix)
    {
        $params = $this->configService->getSortedParameters($prefix);
        $this->twig->display('admin/config/list.twig', $params);
    }

    public function update($id)
    {
        if ($this->superglobals->getMethod() == 'POST') {
            $prefix = $this->superglobals->getPrefix('name');
            if ($prefix == "mb_" || $prefix == "sd_"){
                $this->superglobals->setPost("value", Encryption::encrypt(trim($this->superglobals->getPost('value'))));
            }
            $this->configService->update($id, $this->superglobals->getPost());
            header('Location: /admin/configs');
        }
        $params = $this->configService->get($id);
        $this->twig->display('admin/config/edit.twig', $params);
    }
}
