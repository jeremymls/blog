<?php

use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testClass()
    {
        $category = new Application\Models\Category();
        $this->assertInstanceOf(Application\Models\Category::class, $category);
    }

    public function testTableName()
    {
        $category = new Application\Models\Category();
        $this->assertEquals('categories', $category::TABLE);
    }
    
    public function testFillable()
    {
        $category = new Application\Models\Category();
        $this->assertEquals(['name'], $category->getFillable());
    }
}
