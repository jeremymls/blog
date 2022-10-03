<?php

namespace Application\Repositories;

use Core\Repositories\Repository;
use Application\Models\Comment;

class CommentRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Comment();
    }
}
