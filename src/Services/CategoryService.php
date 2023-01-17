<?php

namespace Application\Services;

use Application\Models\Category;
use Core\Services\EntityService;

/**
 * CategoryService
 * 
 * Category Service
 */
class CategoryService extends EntityService
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
