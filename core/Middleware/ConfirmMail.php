<?php

namespace Core\Middleware;

use Core\Middleware\Session\UserSession;
use Core\Services\FlashService;

class ConfirmMail
{
    public function __construct()
    {
        self::validatedEmail();
    }

    private function validatedEmail()
    {
        $user = UserSession::getInstance();
        if ($user->isUser() && !$user->isValidate() && !$user->isAdmin()) {
            FlashService::getInstance()->template("mail_not_validated");
        }
    }
}
