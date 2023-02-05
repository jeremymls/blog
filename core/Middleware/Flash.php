<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Middleware;

use Core\Controllers\Controller;
use Core\Services\FlashService;

/**
 * Flash
 *
 * Middleware to set the flashs in the twig global variables
 *
 * @category Core
 * @package  Core\Middleware
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class Flash extends Controller
{
    /**
     * __construct
     *
     * Launch the setFlashs method
     *
     * @param mixed $twig Twig instance
     */
    public function __construct($twig)
    {
        self::setFlashs($twig);
    }

    /**
     * Set Flashs
     *
     * Set the flashs in the twig global variables
     *
     * @param mixed $twig Twig instance
     *
     * @return void
     */
    private static function setFlashs($twig)
    {
        $flashs = FlashService::getInstance()->get();
        $twig->addGlobal('flashs', $flashs);
    }
}
