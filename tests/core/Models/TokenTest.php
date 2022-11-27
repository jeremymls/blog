<?php

use Core\Models\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function testClass()
    {
        $token = new Token();
        $this->assertInstanceOf(Core\Models\Token::class, $token);
    }

    public function testTableName()
    {
        $token = new Token();
        $this->assertEquals('tokens', $token::TABLE);
    }

    public function testLinks()
    {
        $token = new Token();
        $this->assertEquals(['user_id' => 'UserRepository'], $token->getLinks());
    }
}