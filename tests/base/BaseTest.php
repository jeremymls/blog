<?php
namespace Test\base;

use Core\Middleware\Superglobals;
use Core\Services\CsrfService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

$phinxInit = true;
require_once('index.php');
class BaseTest extends TestCase
{
    public $host = 'http://blog.jrm.test/';

    public function __construct()
    {
        parent::__construct();
        $this->client = HttpClient::create();
        $this->mockBuild();
        $this->superglobals = Superglobals::getInstance();
    }

    private function mockBuild()
    {
        $mock_csrf = $this->createStub(CsrfService::class);
        $mock_csrf->method('checkToken')->willReturn(true);
    }
}