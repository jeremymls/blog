<?php

use Core\Models\MailContactModel;
use PHPUnit\Framework\TestCase;

class MailContactModelTest extends TestCase
{
    public function testClass()
    {
        $mailContact = new MailContactModel();
        $this->assertInstanceOf(MailContactModel::class, $mailContact);
    }

    public function testFillable()
    {
        $mailContact = new MailContactModel();
        $this->assertEquals(['name','email','message'], $mailContact->getFillable());
    }
}