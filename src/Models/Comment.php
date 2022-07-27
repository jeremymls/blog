<?php

namespace Application\Models;

use Core\Model;

class Comment extends Model
{
    const TABLE = 'comments';

    public $post;
    public $author;
    public string $comment;
    public string $moderate;
}