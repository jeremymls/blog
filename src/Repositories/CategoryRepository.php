<?php

namespace Application\Repositories;

use Application\Models\Category;
use Core\Repositories\Repository;

/**
 * CategoryRepository
 * 
 * Category Repository
 */
class CategoryRepository extends Repository
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Category();
    }
}
