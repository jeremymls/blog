<?php

use Core\Services\ConfigService;
use Core\Services\PhinxService;
use Test\base\BaseTest;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;

class StartTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testChangeEnv()
    {
        if ($_ENV['APP_ENV'] !== "TEST") ConfigService::change_env('TEST');
        $this->assertTrue(true);
        sleep(1);
    }
    
    public function testCreateDb()
    {
        $response = $this->client->request('POST', $this->host."create_bdd");
        $this->assertEquals(200, $response->getStatusCode());
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
        $store = new Store('/tmp');
        $this->client = new CachingHttpClient($this->client, $store);
        $post = [
            'body' => [
                'identifiant' => 'admin',
                'password' => 'jmp2022',
            ]
        ];
        $response = $this->client->request('POST', $this->host."login", $post);
        // I'm trying to test if the user is logged in
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->host."login", $response->getInfo('url'));
        // If he is, I want to test if he can access the dashboard page
        $response = $this->client->request('GET', $this->host."dashboard");
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->host."dashboard", $response->getInfo('url'));
    }

    public function testSeedDatabase()
    {
        PhinxService::getManager()->seed('TEST');

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
    }
}