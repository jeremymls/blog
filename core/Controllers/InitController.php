<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

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
 *
 * @category Core
 * @package  Core\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
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
     * Grant Access
     *
     * If the session variable 'safe_mode' is true, then grant access
     * otherwise, run the Security class
     *
     * @return void
     */
    private static function grantAccess()
    {
        if (PHPSession::getInstance()->get('safe_mode') != true) {
            new Security();
        }
    }

    /**
     * New
     *
     * It displays a form to create a new database
     *
     * @return void
     */
    public function new()
    {
        $dbData = Superglobals::getInstance()->getDatabase();
        // todo: add instructions
        $sql = "CREATE DATABASE IF NOT EXISTS " . $dbData['name'];
        $this->twig->display(
            'admin/config/new.twig',
            [
                'sql' => $sql,
                'bdd_name' => $dbData['name']
            ]
        );
    }

    /**
     * Create
     *
     * It creates a database, then it migrates it.
     *
     * @return void
     */
    public function create()
    {
        InitService::create();
        InitService::migration($this->superglobals->getAppEnv());
    }

    /**
     * Seed
     *
     * If the seedKey is correct, then seed the database
     *
     * @param string $env The environment you want to seed.
     *
     * @return void
     */
    public function seed($env)
    {
        $seed_key = 'r*Bvd2dMpTdGYjwaG^BAw$hADm8gb#KggKxNh9fGv^e6PdU74n';
        // todo: à revoir (aide: ligne 169)
        if (isset($_POST["seedKey"]) && $_POST["seedKey"] == $seed_key) {
            InitService::seed($env);
            // echo 'La base de données a été peuplée avec succès';
        } else {
            throw new \Exception('La clé de peuplement est incorrecte');
            // echo 'La clé de peuplement est incorrecte';
        }
    }

    /**
     * Init
     *
     * Displays the init.twig template
     *
     * @return void
     */
    public function init()
    {
        $configService = new ConfigService();
        $params = $configService->init();
        $this->twig->display('admin/config/init.twig', $params);
    }

    /**
     * Init Configs
     *
     * It creates configs table if it doesn't exist,
     * then it initializes the missing configs
     * and it redirects to the home page
     *
     * @return void
     */
    public function initConfigs()
    {
        $configService = new ConfigService();
        $configService->createConfigTable('configs');
        $configService->initConfigs();
        FlashService::getInstance()->success(
            "Configuration initialisée",
            "La configuration a été initialisée avec succès"
        );
        $this->superglobals->redirect('home');
    }

    /**
     * Delete
     *
     * If the secret key is correct, then it deletes the database
     *
     * @return void
     */
    public function delete()
    {
        $secret = $this->superglobals->getPost('secret');
        if ($secret != $this->superglobals->getSecretKey()) {
            throw new \Exception();
        } else {
            $this->initService->delete();
        }
    }
}
