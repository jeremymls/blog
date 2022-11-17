<?php

namespace Core\Models;

class MailContactModel extends Model
{
    public $name;
    public $email;
    public $phone;
    public $message;

    public function getFillable(): array
    {
        return ['name', 'email', 'message'];
    }
}
