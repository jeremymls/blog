<?php

use PHPUnit\Framework\TestCase;

class DotEnvTest extends TestCase
{
    public function testDotEnv()
    {
        $this->assertNotNull($_ENV['DEV_DB_HOST'], 'DEV_DB_HOST is null');
        $this->assertNotNull($_ENV['DEV_DB_NAME'], 'DEV_DB_NAME is null');
        $this->assertNotNull($_ENV['DEV_DB_USER'], 'DEV_DB_USER is null');
        $this->assertNotNull($_ENV['DEV_DB_PASS'], 'DEV_DB_PASS is null');
        $this->assertNotNull($_ENV['DEV_DB_PORT'], 'DEV_DB_PORT is null');

        $this->assertNotNull($_ENV['TEST_DB_HOST'], 'TEST_DB_HOST is null');
        $this->assertNotNull($_ENV['TEST_DB_NAME'], 'TEST_DB_NAME is null');
        $this->assertNotNull($_ENV['TEST_DB_USER'], 'TEST_DB_USER is null');
        $this->assertNotNull($_ENV['TEST_DB_PASS'], 'TEST_DB_PASS is null');
        $this->assertNotNull($_ENV['TEST_DB_PORT'], 'TEST_DB_PORT is null');

        $this->assertNotNull($_ENV['PROD_DB_HOST'], 'PROD_DB_HOST is null');
        $this->assertNotNull($_ENV['PROD_DB_NAME'], 'PROD_DB_NAME is null');
        $this->assertNotNull($_ENV['PROD_DB_USER'], 'PROD_DB_USER is null');
        $this->assertNotNull($_ENV['PROD_DB_PASS'], 'PROD_DB_PASS is null');
        $this->assertNotNull($_ENV['PROD_DB_PORT'], 'PROD_DB_PORT is null');
    }
}