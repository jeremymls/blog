<?php

use Test\base\RoutesBaseTest;

class StartTest extends RoutesBaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    // Test if the home page is accessible
    public function testHome()
    {
        $response = $this->client->request('GET', $this->host);
        if ($response->getStatusCode() == 200) {
            $response = $this->client->request('GET', $this->host."delete_bdd");
            $this->assertEquals(302, $response->getStatusCode());
        } else {
            $this->assertTrue(true);
        }
    }

    // First start without database
    public function testInitialState()
    {
        $response = $this->client->request('GET', $this->host);
        $statusCode = $response->getStatusCode();
        $this->assertEquals(302, $statusCode);
        $response_url = $response->getInfo('url');
        var_dump($response);
        $this->assertEquals($this->host."login", $response_url); // ! Pourquoi login et pas new?
    }

    // Create database
    public function testCreateDatabase()
    {
        $response = $this->client->request('GET', $this->host."create_bdd");
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host."login", $response_url); // ! Pourquoi login et pas init?
    }

    // Initial parameters
    public function testInitialParameters()
    {
        $response = $this->client->request('POST', $this->host."init");
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host, $response_url); // OK
    }

}