<?php
namespace Test\base;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class RoutesBaseTest extends TestCase
{
    public $host = 'http://blog.jrm.test/';

    public function __construct()
    {
        parent::__construct();
        $this->client = HttpClient::create();
    }
}