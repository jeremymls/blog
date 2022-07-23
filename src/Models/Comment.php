<?php

namespace Application\Models;

use Application\Repositories\UserRepository;

class Comment extends Model
{
    const TABLE = 'comments';

    public $post;
    public $author;
    public string $comment;
    public string $moderate;
}