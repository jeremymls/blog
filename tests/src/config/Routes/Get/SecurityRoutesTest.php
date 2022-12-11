<?php

use Test\base\BaseTest;

class SecurityRoutesTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    // Public routes
    public function testCallLoginRoutes()
    {
        $response = $this->client->request('GET', $this->host . 'login');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'login', $response_url);
    }

    // User not logged
    public function testCallUserProfilRouteNotLogged()
    {
        $response = $this->client->request('GET', $this->host . 'profil');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'login', $response_url);
    }

    public function testCallUserProfilEditRouteNotLogged()
    {
        $response = $this->client->request('GET', $this->host . 'profil/edit');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'login', $response_url);
    }

    public function testCallUserProfilEditMailRouteNotLogged()
    {
        $response = $this->client->request('GET', $this->host . 'profil/edit/mail');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'login', $response_url);
    }

    public function testCallUserProfilEditPasswordRouteNotLogged()
    {
        $response = $this->client->request('GET', $this->host . 'profil/edit/password');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'login', $response_url);
    }

}