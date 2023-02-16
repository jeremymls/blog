<?php
namespace Test\base;

use Core\Middleware\Superglobals;
use Core\Services\CsrfService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;

$noRunRoutes = true;
include_once('index.php');
if (session_status() === PHP_SESSION_NONE) session_start();
class BaseTest extends TestCase
{
    protected $client;
    protected $superglobals;
    protected $host;

    public function __construct()
    {
        parent::__construct();
        $this->client = HttpClient::create();
        $this->mockBuild();
        $this->superglobals = Superglobals::getInstance();
        $this->host = $this->superglobals->getHost();
    }

    private function mockBuild()
    {
        $mock_csrf = $this->createStub(CsrfService::class);
        $mock_csrf->method('checkToken')->willReturn(true);
    }

    public function login($role = 'user')
    {
        $store = new Store('/tmp');
        $this->client = new CachingHttpClient($this->client, $store);
        $post = [
            'body' => [
                'identifiant' => $role,
                'password' => 'jmp2022',
            ]
        ];
        $response = $this->client->request('POST', $this->host."login", $post);
        // test if the login is successful
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->host."login", $response->getInfo('url'));
    }
}