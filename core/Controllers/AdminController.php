<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Controllers;

use Core\Middleware\Security;

/**
 * AdminController
 *
 * Make sure that the user is logged in before accessing any of the admin pages
 *
 * @category Core
 * @package  Core\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
abstract class AdminController extends Controller
{
    /**
     * __construct
     *
     * Launch the Security middleware
     */
    public function __construct()
    {
        parent::__construct();
        new Security();
    }
}
