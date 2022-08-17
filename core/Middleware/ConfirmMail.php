<?php

namespace Core\Middleware;

class ConfirmMail
{
    public function __construct($twig)
    {
        if (isset($_SESSION['user']) &&  $_SESSION['user']->validated_email != "1") {
            $twig->addGlobal('mail_not_validated', true);
        }
    }
}