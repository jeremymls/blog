<?php

namespace Core\Middleware;

use Core\Controllers\Controller;

class Flash extends Controller
{
    public function __construct($twig)
    {
        self::setFlashs($twig);
    }

    private static function setFlashs($twig)
    {
        $shm = shm_attach(SHM_FLASH);
        $i = 1;
        $flashs = [];
        while (shm_has_var($shm, $i)) {
            $flashs[] = (shm_get_var($shm, $i));
            shm_remove_var($shm, $i);
            $i++;
        }
        $twig->addGlobal('flashs', $flashs);
    }
}
