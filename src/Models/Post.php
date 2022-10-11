<?php

namespace Application\Models;

use Core\Models\Model;

class Post extends Model
{
    const TABLE = 'posts';
    
    public $category;
    public string $title;
    public string $content;
    public string $url;
    public string $chapo;
    public string $picture;

    public function getFillable(): array
    {
        return [
            'title',
            'content',
        ];
    }

    public function getLinks()
    {
        return [
            'category' => 'CategoryRepository',
        ];
    }
}
