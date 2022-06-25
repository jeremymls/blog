<?php

namespace Application\Controllers;

class Error
{
    public $code;
    public $message;
    public $file;
    public $line;
    public $trace;
}

class ErrorException extends Controller
{
    
    public function execute($err)
    {
        $error = new Error();
        $error->code = $err->getCode();
        $error->message = $err->getMessage();
        $error->file = $err->getFile();
        $error->line = $err->getLine();
        $error->trace = $err->getTraceAsString();

        $this->twig->display('error.twig', [
            'error' => $error,
        ]);
    }
}
