<?php

namespace Core\Services;

use Core\Models\Config;
use Core\Repositories\InitRepository;

class InitService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->initRepository = new InitRepository();
    }

    public function create()
    {
        $this->initRepository->create_database();
    }
}
