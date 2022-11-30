<?php

use Application\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testClass()
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
    }

    public function testTableName()
    {
        $user = new User();
        $this->assertEquals('users', $user::TABLE);
    }
    
    public function testFillable()
    {
        $user = new User();
        $this->assertEquals(['password','email','first', 'last', 'role'], $user->getFillable());
    }

    public function testPassword()
    {
        $user = new User();
        $password = 'password';
        $user->setPassword($password);
        $this->assertTrue($user->comparePassword($user->password, $password));
    }
}