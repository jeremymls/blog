<?php

namespace Application\Models;

use Core\Models\Model;

/**
 * Comment
 * 
 * Comment Model
 */
class Comment extends Model
{
    const TABLE = 'comments';

    public $post;
    public $author;
    public string $comment;
    public string $moderate;
    public string $deleted;

    public function getLinks()
    {
        return [
            'post' => 'PostRepository',
            'author' => 'UserRepository',
        ];
    }

    public function getFillable(): array
    {
        return [
            'post',
            'author',
            'comment',
        ];
    }
}
