<?php

use Core\Services\ConfigService;
use Test\base\BaseTest;

class EndTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testDeleteDb()
    {
        $response = $this->client->request('GET', $this->host."delete_bdd");
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($this->host."new", $response->getInfo('url'));
    }

    public function testChangeEnv()
    {
        ConfigService::change_env('DEV');
        $this->assertTrue(true);
    }
}