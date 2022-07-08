<?php

namespace Application\Models;

class UserModel
{
    public string $username;
    public string $password;
    public string $email;
    public string $first;
    public string $last;
    public string $role;
    public int $id;
    public string $validated_email;
}