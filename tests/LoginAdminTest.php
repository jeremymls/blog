<?php
namespace Test;

use Core\Services\CsrfService;
use Test\base\BaseTest;

class LoginAdminTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testAuthAdmin()
    {
        $response = $this->client->request('POST', $this->host."login", [
            'body' => [
                'identifiant' => 'admin',
                'password' => 'jmp2022',
                'csrf_token' => "csrf_token"
            ]
        ]);
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
    }
}