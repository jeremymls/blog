<?php

use Application\Models\User;

class UserTest extends User
{
    public function getUser()
    {
        $user = new User();
        $user->setId(1);
        $user->setCreatedAt('2021-01-01 00:00:00');
        $user->setPassword('password');
        $user->username = 'username';
        $user->email = 'email';
        $user->first = 'first';
        $user->last = 'last';
        $user->role = 'user';
        $user->validated_email = 1;
        return $user;
    }
}