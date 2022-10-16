<?php

namespace Core\Middleware;

use Core\Middleware\Session\UserSession;
use Core\Services\FlashService;

class Security
{
    public function __construct()
    {
        self::isGranted();
    }

    private static function isGranted()
    {
        $userSession = new UserSession();
        $flashServices = new FlashService();
        if ($userSession->get("safe_mode")) {
            return true;
        }
        if (!$userSession->isUser()) {
            $flashServices->warning('Accès non autorisé', 'Vous n\'avez pas accès à cette page! Veuillez vous connecter');
            header('Location: /login');
        } else {
            if (!$userSession->isAdmin()) {
                throw new \Exception('Vous n\'avez pas le droit d\'accéder à cette page', 403);
            }
        }
    }
}
