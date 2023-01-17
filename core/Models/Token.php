<?php

namespace Core\Models;

/**
 * Token model
 */
class Token extends Model
{
    const TABLE = 'tokens';

    public $user_id;
    public string $token;
    public string $expiration_date;

    public function getLinks()
    {
        return ['user_id' => 'UserRepository'];
    }
}
