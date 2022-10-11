<?php

namespace Application\Services;

use Application\Models\Category;
use Core\Services\EntityService;

class CategoryService extends EntityService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new Category();
    }
}
