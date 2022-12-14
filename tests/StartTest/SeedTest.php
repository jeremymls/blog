<?php

use Core\Services\PhinxService;
use Test\base\BaseTest;

class SeedTest extends BaseTest
{
    protected $client;
    public function __construct()
    {
        parent::__construct();
    }

    public function testSeedDatabase()
    {
        sleep(3);
        PhinxService::getManager()->AppSeed('TEST');

        $response = $this->client->request('GET', $this->host . 'posts/5');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'posts/5', $response_url);

        $response = $this->client->request('GET', $this->host . 'post/100');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'post/100', $response_url);
        sleep(3);
        echo "StartTest finished".PHP_EOL;
    }
}