<?php

namespace Application\Controllers;

use Application\Models\ErrorModel;

class ErrorExceptionController extends Controller
{
    
    public function execute($err)
    {
        $error = new ErrorModel();
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
