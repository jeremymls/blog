<?php

use Test\base\BaseTest;

class CreateDbTest extends BaseTest
{
    protected $client;
    public function __construct()
    {
        parent::__construct();
    }

    public function testCreateDb()
    {
        $response = $this->client->request('POST', $this->host."create_bdd");
        $this->assertEquals(200, $response->getStatusCode());
        echo "Database created".PHP_EOL;
        sleep(3);
    }
}