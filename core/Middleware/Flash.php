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
        $flashs = FlashService::getInstance()->get();
        $twig->addGlobal('flashs', $flashs);
    }
}
