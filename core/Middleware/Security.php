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
 * Security
 *
 * Middleware to check if the user is logged in and if he is an admin
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Security
{
    /**
     * __construct
     *
     * Launch the isGranted method
     */
    public function __construct()
    {
        $this->isGranted();
    }

    /**
     * Is Granted
     *
     * Check if the user is logged in and if he is an admin
     *
     * @return mixed
     */
    private function isGranted()
    {
        $userSession = UserSession::getInstance();
        if ($userSession->get("safe_mode")) {
            return true;
        }
        if (!$userSession->isUser()) {
            FlashService::getInstance()->warning(
                'Accès non autorisé',
                'Vous n\'avez pas accès à cette page! 
                <strong style="color:red;">Veuillez vous connecter!</strong>'
            );
            Superglobals::getInstance()->redirect('login');
        } else {
            if (!$userSession->isAdmin()) {
                throw new \Exception(
                    'Vous n\'avez pas le droit d\'accéder à cette page',
                    403
                );
            }
        }
    }
}
