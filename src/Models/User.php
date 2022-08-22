<?php

namespace Application\Models;

use Core\Models\Model;

class User extends Model
{
    const TABLE = 'users';

    public string $username;
    public string $password;
    public string $email;
    public string $first;
    public string $last;
    public string $role;
    public string $validated_email;
    public string $picture;

    public function setPassword($password)
    {
        $this->password = hash("sha512",hash("ripemd256", $password));
    }

    public function comparePassword($passwordBDD, $passwordPOST)
    {
        return hash("sha512",hash("ripemd256", $passwordPOST)) == $passwordBDD;
    }
}