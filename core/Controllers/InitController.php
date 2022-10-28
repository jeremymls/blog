<?php

namespace Core\Controllers;

use Core\Middleware\Security;
use Core\Middleware\Session\PHPSession;
use Core\Services\ConfigService;
use Core\Services\InitService;

require_once 'src/config/database.php';

class InitController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->grantAccess();
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
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
        $this->twig->display(
            'admin/config/new.twig', [
            'sql' => $sql,
            'bdd_name' => DB_NAME,
            ]
        );
    }

    public function init()
    {
        $configService = new ConfigService();
        $params = $configService->init();
        $this->twig->display('admin/config/init.twig', $params);
    }

    public function create()
    {
        $initService = new InitService();
        $initService->create();
    }
}
