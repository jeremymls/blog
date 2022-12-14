<?php

use Test\base\BaseTest;

class AuthAdminTest extends BaseTest
{
    protected $client;
    public function __construct()
    {
        parent::__construct();
    }

    // Test if the home page is accessible
    public function testHome()
    {
        $response = $this->client->request('GET', $this->host);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->host, $response->getInfo('url'));
    }

    public function testAuthAdmin()
    {
        $this->login("admin");
        // If he is, I want to test if he can access the dashboard page
        $response = $this->client->request('GET', $this->host."dashboard");
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->host."dashboard", $response->getInfo('url'));
    }
}