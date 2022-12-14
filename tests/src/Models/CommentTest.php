<?php

use Application\Models\Comment;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testClass()
    {
        $comment = new Comment();
        $this->assertInstanceOf(Comment::class, $comment);
    }

    public function testTableName()
    {
        $comment = new Comment();
        $this->assertEquals('comments', $comment::TABLE);
    }
    
    public function testFillable()
    {
        $comment = new Comment();
        $this->assertEquals(['post','author','comment'], $comment->getFillable());
    }

    public function testLinks()
    {
        $comment = new Comment();
        $this->assertEquals(['post' => 'PostRepository', 'author' => 'UserRepository' ], $comment->getLinks());
    }
}