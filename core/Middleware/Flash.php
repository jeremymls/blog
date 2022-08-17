<?php

namespace Core\Middleware;

use Core\Controller;

class Flash extends Controller
{
    public function __construct($twig)
    {
        if (isset($_COOKIE['flash'])) {
            $twig->addGlobal('type', $_COOKIE['type']);
            $twig->addGlobal('title', $_COOKIE['title']);
            $twig->addGlobal('message', $_COOKIE['message']);
            $twig->addGlobal('flash', $_COOKIE['flash']);
        } 
    }
}