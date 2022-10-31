<?php

namespace Core\Controllers;

use Core\Middleware\Security;
use Core\Middleware\Session\PHPSession;
use Core\Middleware\Superglobals;
use Core\Services\ConfigService;
use Core\Services\FlashService;
use Core\Services\InitService;

class InitController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->grantAccess();
        $this->configService = new ConfigService();
    }

    private static function grantAccess()
    {
        if (PHPSession::getInstance()->get('safe_mode') == true) {
            return true;
        } else {
            new Security();
        }
    }

    public function new()
    {
        $dbData = Superglobals::getInstance()->getDatabase();
        $sql = "CREATE DATABASE IF NOT EXISTS " . $dbData['name'];
        $this->twig->display(
            'admin/config/new.twig', [
            'sql' => $sql,
            'bdd_name' => $dbData['name'],
            ]
        );
    }

    public function init()
    {
        $params = $this->configService->init();
        $this->twig->display('admin/config/init.twig', $params);
    }

    public function create()
    {
        $initService = new InitService();
        $initService->create();
    }

    public function init_configs()
    {
        $this->configService->create_config_table('configs');
        $this->configService->initConfigs();
        FlashService::getInstance()->success("Configuration initialisée", "La configuration a été initialisée avec succès");
        $this->superglobals->redirect('home');
    }

    public function init_missing_configs()
    {
        $this->configService->initConfigs();
        FlashService::getInstance()->success("Configuration initialisée", "La configuration a été initialisée avec succès");
        $this->superglobals->redirect('home');
    }

    public function init_tables()
    {
        $this->configService->create_tables();
        $this->superglobals->redirect('home');
    }
}
