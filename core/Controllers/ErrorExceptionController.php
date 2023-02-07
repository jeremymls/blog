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

use Core\Controllers\Controller;
use Core\Models\Error;

/**
 * ErrorExceptionController
 *
 * Display the error page
 *
 * @category Core
 * @package  Core\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class ErrorExceptionController extends Controller
{
    /**
     * Execute
     *
     * It takes an error object, and displays it in a nice format
     *
     * @param object $err           Error object that is passed to the error handler.
     * @param string $customMessage Custom message
     *
     * @return void
     */
    public function execute($err, $customMessage = false)
    {
        $error = new Error();
        $error->code = $err->getCode() != 0 ? $err->getCode() : 500;
        $error->message = $customMessage ? $customMessage : $err->getMessage();
        $error->file = $err->getFile();
        $error->line = $err->getLine();
        $error->trace = $err->getTraceAsString();
        http_response_code($error->code);
        $this->twig->display('error.twig', ['error' => $error,]);
    }

    /**
     * Access Denied
     *
     * @return void
     */
    public function accessDenied()
    {
        $this->twig->display('403.twig');
    }
}
