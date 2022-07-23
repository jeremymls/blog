<?php

namespace Application\Models;

use Application\Repositories\UserRepository;

class CommentModel extends Model
{
    const TABLE = 'comments';

    public $post;
    public $author;
    public string $comment;
    public string $moderate;
}