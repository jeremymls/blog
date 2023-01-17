<?php

namespace Core\Controllers;

use Core\Middleware\Security;
use Core\Middleware\Session\PHPSession;
use Core\Middleware\Superglobals;
use Core\Services\ConfigService;
use Core\Services\FlashService;
use Core\Services\InitService;
use Core\Services\PhinxService;

/**
 * InitController
 * 
 * This controller is used to initialize the database
 */
class InitController extends Controller
{
    protected $initService;
    protected $superglobals;

    /**
     * Constructor
     * 
     * The function is a constructor function that 
     * * calls the parent constructor function
     * * calls the grantAccess function
     * * creates a new instance of the InitService class
     */
    public function __construct()
    {
        parent::__construct();
        $this->grantAccess();
        $this->initService = new InitService();
    }

    /**
     * grantAccess
     * 
     * If the session variable 'safe_mode' is true, then grant access
     * otherwise, run the Security class
     */
    private static function grantAccess()
    {
        if (PHPSession::getInstance()->get('safe_mode') != true) 
            new Security();
    }

    /**
     * new
     * 
     * It displays a form to create a new database
     */
    public function new()
    {
        $dbData = Superglobals::getInstance()->getDatabase();
        $sql = "CREATE DATABASE IF NOT EXISTS " . $dbData['name']; // todo: add instructions
        $this->twig->display('admin/config/new.twig', ['sql' => $sql,'bdd_name' => $dbData['name']]);
    }
    
    /**
     * create
     * 
     * It creates a database, then it migrates it.
     */
    public function create()
    {
        InitService::create();
        InitService::migration($this->superglobals->getAppEnv());
        echo 'La base de données a été créée avec succès';
    }

    /**
     * seed
     * 
     * If the seedKey is correct, then seed the database
     * 
     * @param string $env The environment you want to seed.
     */
    public function seed($env)
    {
        if ($_POST["seedKey"] == 'r*Bvd2dMpTdGYjwaG^BAw$hADm8gb#KggKxNh9fGv^e6PdU74n') { // todo: à revoir
            InitService::seed($env);
            echo 'La base de données a été peuplée avec succès';
        } else {
            echo 'La clé de peuplement est incorrecte';
        }
    }

    /**
     * init
     * 
     * Displays the init.twig template
     */
    public function init()
    {
        $configService = new ConfigService();
        $params = $configService->init();
        $this->twig->display('admin/config/init.twig', $params);
    }

    /**
     * init_configs
     * 
     * It creates configs table if it doesn't exist, then it initializes the missing configs
     * and it redirects to the home page
     */
    public function init_configs()
    {
        $configService = new ConfigService();
        $configService->create_config_table('configs');
        $configService->initConfigs();
        FlashService::getInstance()->success("Configuration initialisée", "La configuration a été initialisée avec succès");
        $this->superglobals->redirect('home');
    }

    /**
     * delete
     * 
     * If the secret key is correct, then it deletes the database
     */
    public function delete()
    {
        if ($this->superglobals->getPost('secret') != $this->superglobals->getSecretKey()) {
            echo 'error';
        } else {
            $this->initService->delete();
            echo 'done';
        }
    }
}
