<?php

namespace Core\Services;

use Core\Models\Config;
use Core\Repositories\InitRepository;

class InitService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function create()
    {
        InitRepository::create_database();
    }

    public static function migration($env)
    {
        $phinx = PhinxService::getManager();
        $phinx->AppMigrate($env);
    }

    public static function seed($env)
    {
        $phinx = PhinxService::getManager();
        $phinx->AppSeed($env);
    }

    public function create_tables() // todo: à revoir
    {
        $this->getRepository()->create_tables();
    }

    public function delete()
    {
        $this->getRepository()->delete_database();
    }
}
