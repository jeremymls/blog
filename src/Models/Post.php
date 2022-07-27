<?php

namespace Application\Models;

use Core\Model;

class Post extends Model
{
    const TABLE = 'posts';
    
    public string $title;
    public string $content;
    public string $url;
    public string $chapo;
}