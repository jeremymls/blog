<?php

use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testClass()
    {
        $comment = new Application\Models\Comment();
        $this->assertInstanceOf(Application\Models\Comment::class, $comment);
    }

    public function testTableName()
    {
        $comment = new Application\Models\Comment();
        $this->assertEquals('comments', $comment::TABLE);
    }
    
    public function testFillable()
    {
        $comment = new Application\Models\Comment();
        $this->assertEquals(['post','author','comment'], $comment->getFillable());
    }
}