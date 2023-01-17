<?php

namespace Application\Repositories;

use Core\Repositories\Repository;
use Application\Models\Comment;

/**
 * CommentRepository
 * 
 * Comment Repository
 */
class CommentRepository extends Repository
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Comment();
    }
}
