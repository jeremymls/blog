<?php

namespace Application\Models;

use Core\Models\Model;

class Comment extends Model
{
    const TABLE = 'comments';

    public $post;
    public $author;
    public string $comment;
    public string $moderate;
}