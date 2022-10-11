<?php

namespace Application\Repositories;

use Application\Models\Category;
use Core\Repositories\Repository;

class CategoryRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Category();
    }
}
