<?php

namespace Core\Middleware;

use Core\Middleware\Session\UserSession;
use Core\Services\FlashService;

/**
 * ConfirmMail
 * 
 * Middleware to check if the user has validated his email
 * and if not, display a message
 */
class ConfirmMail
{    
    /**
     * __construct
     *
     * Launch the validatedEmail method
     */
    public function __construct()
    {
        self::validatedEmail();
    }

    /**
     * validatedEmail
     * 
     * Check if the user has validated his email and if not, display a message
     */
    private function validatedEmail()
    {
        $user = UserSession::getInstance();
        if ($user->isUser() && !$user->isValidate() && !$user->isAdmin()) {
            FlashService::getInstance()->template("mail_not_validated");
        }
    }
}
