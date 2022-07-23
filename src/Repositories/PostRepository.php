<?php

namespace Application\Repositories;

use Application\Models\Post;

class PostRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Post();
    }
}
