<?php

namespace Application\Models;

use Application\Repositories\UserRepository;

class UserModel extends Model
{
    const TABLE = 'users';

    public string $username;
    public string $password;
    public string $email;
    public string $first;
    public string $last;
    public string $role;
    public string $validated_email;
}