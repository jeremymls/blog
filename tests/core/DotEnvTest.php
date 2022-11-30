<?php

use PHPUnit\Framework\TestCase;

class DotEnvTest extends TestCase
{
    public function testDotEnv()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__,2));
        $dotenv->load();
        $this->assertNotNull($_ENV['DB_HOST'], 'DB_HOST is null');
        $this->assertNotNull($_ENV['DB_NAME'], 'DB_NAME is null');
        $this->assertNotNull($_ENV['DB_USER'], 'DB_USER is null');
        $this->assertNotNull($_ENV['DB_PASS'], 'DB_PASS is null');
    }
}