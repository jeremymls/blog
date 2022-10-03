<?php

namespace Core\Models;

class Config extends Model
{
    const TABLE = 'configs';

    public string $name;
    public ?string $value;
    public ?string $description;

    public function getFillable(): array
    {
        return ['name'];
    }
}
