<?php

namespace Application\Repositories;

use Core\Repositories\Repository;
use Application\Models\Post;

/**
 * PostRepository
 * 
 * Post Repository
 */
class PostRepository extends Repository
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Post();
    }
}
