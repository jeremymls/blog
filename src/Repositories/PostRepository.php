<?php

namespace Application\Repositories;

use Application\Models\PostModel;

class PostRepository extends Repository
{
    public function __construct()
    {
        $this->model = new PostModel();
    }
}
