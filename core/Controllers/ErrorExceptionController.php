<?php

namespace Core\Controllers;

use Core\Controllers\Controller;
use Core\Models\Error;

class ErrorExceptionController extends Controller
{
    
    public function execute($err)
    {
        $error = new Error();
        $error->code = $err->getCode() != 0 ? $err->getCode():500;
        $error->message = $err->getMessage();
        $error->file = $err->getFile();
        $error->line = $err->getLine();
        $error->trace = $err->getTraceAsString();
        http_response_code($error->code);
        $this->twig->display('error.twig', ['error' => $error,]);
    }
}
