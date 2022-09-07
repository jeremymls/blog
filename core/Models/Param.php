<?php

namespace Core\Models;

class Param extends Model
{
    const TABLE = 'params';

    public string $name;
    public ?string $value;
    public ?string $description;
    
}