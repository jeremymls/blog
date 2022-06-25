<?php

namespace Application\Controllers;


class ErrorException extends Controller
{
    public function execute($errorMessage)
    {
        $this->twig->display('error.twig', [
            'errorMessage' => $errorMessage,
        ]);
    }
}
