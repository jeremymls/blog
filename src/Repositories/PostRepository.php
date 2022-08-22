<?php

namespace Application\Repositories;

use Core\Repositories\Repository;
use Application\Models\Post;

class PostRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Post();
    }
}
