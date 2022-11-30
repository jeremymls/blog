<?php

use Application\Models\Comment;
use Application\Repositories\UserRepository;
use Core\Models\Model;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function testClass()
    {
        $model = new Model();
        $this->assertInstanceOf(Core\Models\Model::class, $model);
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

    // public function testWith()
    // {
    //     $comment = new Comment();
    //     $comment->setId(22);
    //     $comment->with('author', UserRepository::class);
    //     var_dump($comment);
    //     $this->assertInstanceOf(Application\Models\User::class, $comment->author);
    // }

    // public function testWithExpirationToken()
    // {
    //     $model = new Core\Models\Model();
    //     $model->setId(1);
    //     $model->withExpirationToken();
    //     $this->assertEquals('expired', $model->token);
    // }
}