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
        $userSession = new UserSession();
        $flashServices = new FlashService();
        if ($userSession->get("safe_mode")) {
            return true;
        }
        if (!$userSession->isUser()) {
            $flashServices->warning('Accès non autorisé', 'Vous n\'avez pas accès à cette page! <strong style="color:red;">Veuillez vous connecter!</strong>');
            $superglobals = new Superglobals();
            $superglobals->redirect('login');
        } else {
            if (!$userSession->isAdmin()) {
                throw new \Exception('Vous n\'avez pas le droit d\'accéder à cette page', 403);
            }
        }
    }
}
