<?php

namespace Application\Models;

use Core\Models\Model;

/**
 * Category
 * 
 * Category model
 */
class Category extends Model
{
    const TABLE = 'categories';
    
    public string $name;

    public function getFillable(): array
    {
        return [
            'name',
        ];
    }
}
