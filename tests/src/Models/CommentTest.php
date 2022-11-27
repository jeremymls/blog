<?php

use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testClass()
    {
        $post = new Application\Models\Post();
        $this->assertInstanceOf(Application\Models\Post::class, $post);
    }

    public function testTableName()
    {
        $post = new Application\Models\Post();
        $this->assertEquals('posts', $post::TABLE);
    }
    
    public function testFillable()
    {
        $post = new Application\Models\Post();
        $this->assertEquals(['title','content'], $post->getFillable());
    }

    public function testLinks()
    {
        $post = new Application\Models\Post();
        $this->assertEquals(['category' => 'CategoryRepository'], $post->getLinks());
    }
}