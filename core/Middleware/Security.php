<?php

namespace Core\Middleware;

use Core\Services\Flash;

class Security
{
    public function __construct()
    {
        $this->flash = new Flash();
        if (!isset($_SESSION['user'])) {
            $this->flash->warning('Accès non autorisé', 'Vous n\'avez pas accès à cette page! Veuillez vous connecter');
            header('Location: /login');
        } else {
            if ($_SESSION['user']->role != 'admin') {
                throw new \Exception('Vous n\'avez pas les droits pour accéder à cette page', 403);
            }
        }
    }
}