<?php

namespace Core\Middleware;

use Core\Controllers\Controller;
use Core\Services\FlashService;

class Flash extends Controller
{
    public function __construct($twig)
    {
        self::setFlashs($twig);
    }

    private static function setFlashs($twig)
    {
        $flashServices = new FlashService();
        $flashs = $flashServices->get();
        $twig->addGlobal('flashs', $flashs);
    }
}
