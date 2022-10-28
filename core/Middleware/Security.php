<?php

namespace Core\Middleware;

use Core\Middleware\Session\UserSession;
use Core\Services\FlashService;

class Security
{
    public function __construct()
    {
        $this->isGranted();
    }

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
