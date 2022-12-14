<?php

use Application\Models\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testClass()
    {
        $post = new Post();
        $this->assertInstanceOf(Post::class, $post);
    }

    public function testTableName()
    {
        $post = new Post();
        $this->assertEquals('posts', $post::TABLE);
    }

    public function testFillable()
    {
        $post = new Post();
        $this->assertEquals(['title','content'], $post->getFillable());
    }

    public function testLinks()
    {
        $post = new Post();
        $this->assertEquals(['category' => 'CategoryRepository'], $post->getLinks());
    }
}