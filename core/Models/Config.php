<?php

namespace Core\Models;

class Config extends Model
{
    const TABLE = 'configs';

    public string $name;
    public ?string $value;
    public ?string $description;
    public ?string $type;
    public ?string $default_value;

    public function getFillable(): array
    {
        return ['name'];
    }
}
