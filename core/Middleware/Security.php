<?php

namespace Core\Middleware;

use Core\Services\FlashService;

class Security
{
    public function __construct()
    {
        self::isGranted();
    }

    private static function isGranted()
    {
        if (!isset($_SESSION['user'])) {
            $flashServices = new FlashService();
            $flashServices->warning('Accès non autorisé', 'Vous n\'avez pas accès à cette page! Veuillez vous connecter');
            header('Location: /login');
        } else {
            if ($_SESSION['user']->role != 'admin') {
                throw new \Exception('Vous n\'avez pas le droit d\'accéder à cette page', 403);
            }
        }
    }
}