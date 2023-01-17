<?php

namespace Core\Controllers;

use Core\Controllers\Controller;
use Core\Models\Error;

/**
 * ErrorExceptionController
 * 
 * Display the error page
 */
class ErrorExceptionController extends Controller
{
    /**
     * execute
     * 
     * It takes an error object, and displays it in a nice format
     *
     * @param object $err This is the error object that is passed to the error handler.
     * @param string $customMessage This is a custom message that you can pass to the error handler.
     */
    public function execute($err, $customMessage=false)
    {
        $error = new Error();
        $error->code = $err->getCode() != 0 ? $err->getCode():500;
        $error->message = $customMessage ? $customMessage:$err->getMessage();
        $error->file = $err->getFile();
        $error->line = $err->getLine();
        $error->trace = $err->getTraceAsString();
        http_response_code($error->code);
        $this->twig->display('error.twig', ['error' => $error,]);
    }
}
