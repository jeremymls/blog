<?php

use Core\Models\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testClass()
    {
        $config = new Config();
        $this->assertInstanceOf(Config::class, $config);
    }

    public function testTableName()
    {
        $config = new Config();
        $this->assertEquals('configs', $config::TABLE);
    }
    
    public function testFillable()
    {
        $config = new Config();
        $this->assertEquals(['name'], $config->getFillable());
    }
}