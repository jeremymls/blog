<?php
namespace Test\base;

use Core\Lib\Singleton;
use Core\Repositories\Repository;
use Core\Services\CsrfService;
use PDO;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpClient\HttpClient;

class BaseTest extends TestCase
{
    public $host = 'http://blog.jrm.test/';

    public function __construct()
    {
        parent::__construct();
        $this->client = HttpClient::create();
        $this->mockBuild();
    }

    protected $conn = null;

    public function getConnection()
    {
        $pdo = new PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $configArray = require('phinx.php');
        $config = new Config($configArray);
        $manager = new Manager($config, new StringInput(''), new NullOutput());
        $manager->migrate('test');
        $manager->seed('test');
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $pdo;
    }

    private function mockBuild()
    {
        $mock_pdo = $this->createMock(Singleton::class);
        $mock_pdo->method('getConnection')->willReturn($this->getConnection());

        $mock_csrf = $this->createMock(CsrfService::class);
        $mock_csrf->method('checkToken')->willReturn(true);
    }
}