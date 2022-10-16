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
        $user = new UserSession();
        $flashServices = new FlashService();
        if ($user->isUser() && !$user->isValidate() && !$user->isAdmin()) {
            $flashServices->template("mail_not_validated");
        }
    }
}
