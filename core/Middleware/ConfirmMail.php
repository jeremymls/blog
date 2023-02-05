<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Middleware;

use Core\Middleware\Session\UserSession;
use Core\Services\FlashService;

/**
 * ConfirmMail
 *
 * Middleware to check if the user has validated his email
 * and if not, display a message
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
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
     * ValidatedEmail
     *
     * Check if the user has validated his email and if not, display a message
     *
     * @return void
     */
    private function validatedEmail()
    {
        $user = UserSession::getInstance();
        if ($user->isUser() && !$user->isValidate() && !$user->isAdmin()) {
            FlashService::getInstance()->template("mail_not_validated");
        }
    }
}
