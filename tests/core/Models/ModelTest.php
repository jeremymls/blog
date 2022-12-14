<?php

use Core\Models\Model;
use Application\Models\Post;
use PHPUnit\Framework\TestCase;
use Application\Models\Category;
use Application\Repositories\CategoryRepository;

class ModelTest extends TestCase
{
    public function testClass()
    {
        $model = new Model();
        $this->assertInstanceOf(Model::class, $model);
    }

    public function testGetSetId()
    {
        $model = new Model();
        $model->setId(1);
        $this->assertEquals(1, $model::$id);
    }

    public function testSetCreatedAt()
    {
        $model = new Model();
        $model->setCreatedAt('2021-01-01 00:00:00');
        $this->assertEquals('2021-01-01 00:00:00', $model::$created_at);
    }

    public function testGetFrenchCreationDate()
    {
        $model = new Model();
        $model->setCreatedAt('2021-01-01 00:00:00');
        $this->assertEquals('01/01/2021 à 00:00', $model->getFrenchCreationDate());
    }

    public function testWith()
    {
        $post = new Post();
        $post->category = "1";
        $post->with('category', CategoryRepository::class);
        $this->assertInstanceOf(Category::class, $post->category);
    }
}
