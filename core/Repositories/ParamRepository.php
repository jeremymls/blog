<?php

namespace Core\Repositories;

use Core\Models\Param;

class ParamRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Param();
    }
}