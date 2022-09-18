<?php

namespace Core\Repositories;

use Core\Models\Config;

class ConfigRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Config();
    }
}