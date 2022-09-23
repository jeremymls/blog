<?php

namespace Core\Models;

class Token extends Model
{
    const TABLE = 'tokens';

    public $user_id;
    public string $token;
    public string $expiration_date;
}
