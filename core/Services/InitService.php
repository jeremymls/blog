<?php

namespace Core\Services;

use Core\Models\Config;
use Core\Repositories\InitRepository;

/**
 * InitService
 * 
 * Create, migrate and seed the database
 */
class InitService extends Service
{    
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * create
     * 
     * Create the database
     */
    public static function create()
    {
        InitRepository::create_database();
    }

    /**
     * migrate
     * 
     * Migrate the database
     */
    public static function migration($env)
    {
        $phinx = PhinxService::getManager();
        $phinx->AppMigrate($env);
    }

    /**
     * seed
     * 
     * Seed the database
     */
    public static function seed($env)
    {
        $phinx = PhinxService::getManager();
        $phinx->AppSeed($env);
    }

    /**
     * delete
     * 
     * Delete the database
     */
    public function delete()
    {
        $this->getRepository()->delete_database();
    }
}
