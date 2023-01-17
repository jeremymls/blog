<?php

namespace Core\Middleware;

use Core\Controllers\Controller;
use Core\Services\FlashService;

/**
 * Flash
 * 
 * Middleware to set the flashs in the twig global variables
 */
class Flash extends Controller
{
    public function __construct($twig)
    {
        self::setFlashs($twig);
    }

    /**
     * setFlashs
     * 
     * Set the flashs in the twig global variables
     *
     * @param  mixed $twig
     */
    private static function setFlashs($twig)
    {
        $flashs = FlashService::getInstance()->get();
        $twig->addGlobal('flashs', $flashs);
    }
}
