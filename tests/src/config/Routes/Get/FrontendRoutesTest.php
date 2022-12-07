<?php

use Test\base\BaseTest;

class FrontendRoutesTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    // FRONTEND //
    // Home
    public function testCallHomeRoutes()
    {
        $response = $this->client->request('GET', $this->host);
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host, $response_url);
    }

    // Post
    public function testCallPostsRoutes()
    {
        $response = $this->client->request('GET', $this->host . 'posts');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'posts', $response_url);
    }

    public function testCallPostsCategoriesRoutes()
    {
        $response = $this->client->request('GET', $this->host . 'posts/categories');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'posts/categories', $response_url);
    }

    public function testCallPostsCategoriesIdRoutes()
    {
        $response = $this->client->request('GET', $this->host . 'posts/1');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'posts/1', $response_url);
    }

    public function testCallPostIdRoutes()
    {
        $response = $this->client->request('GET', $this->host . 'post/1');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'post/1', $response_url);
    }

    // Comment
    public function testCallCommentUpdateRoutes()
    {
        $response = $this->client->request('GET', $this->host . 'comment/1');
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $response_url = $response->getInfo('url');
        $this->assertEquals($this->host . 'comment/1', $response_url);
    }
}