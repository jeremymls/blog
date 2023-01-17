<?php

namespace Core\Middleware;

use Core\Middleware\Session\UserSession;
use Core\Services\FlashService;

/**
 * Security
 * 
 * Middleware to check if the user is logged in and if he is an admin
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
     * isGranted
     * 
     * Check if the user is logged in and if he is an admin
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
                'Vous n\'avez pas accès à cette page! <strong style="color:red;">Veuillez vous connecter!</strong>'
            );
            Superglobals::getInstance()->redirect('login');
        } else {
            if (!$userSession->isAdmin()) {
                throw new \Exception('Vous n\'avez pas le droit d\'accéder à cette page', 403);
            }
        }
    }
}
