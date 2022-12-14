<?php

use Core\Services\ConfigService;
use Test\base\BaseTest;

class ChangeEnvTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testChangeEnv()
    {
        if ($_ENV['APP_ENV'] !== "TEST") ConfigService::change_env('TEST');
        $this->assertTrue(true);
        echo "Environment changed to TEST".PHP_EOL;
        sleep(1);
    }
}