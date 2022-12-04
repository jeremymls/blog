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

    public function create_tables()
    {
        $this->getRepository()->create_tables();
    }

    public function delete()
    {
        $this->getRepository()->delete_database();
    }
}
