<?php

use Core\Models\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testClass()
    {
        $error = new Error();
        $this->assertInstanceOf(Error::class, $error);
        $this->assertObjectHasAttribute('code', $error);
        $this->assertObjectHasAttribute('message', $error);
        $this->assertObjectHasAttribute('file', $error);
        $this->assertObjectHasAttribute('line', $error);
        $this->assertObjectHasAttribute('trace', $error);
    }
}