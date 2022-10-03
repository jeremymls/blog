<?php

namespace Application\Services;

use Application\Models\Post;
use Core\Services\EntityService;

class PostService extends EntityService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new Post();
    }
}
