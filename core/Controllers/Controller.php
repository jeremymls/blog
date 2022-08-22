<?php

namespace Core\Controllers;

use Core\Middleware\ConfirmMail;
use Core\Middleware\Flash;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    private $loader;
    protected $twig;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(ROOT . '/templates');
        $this->twig = new Environment($this->loader, [
            'debug' => true,
        ]);
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new StringExtension());
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('get', $_GET);
        $this->twig->addGlobal('url_request', $_SERVER['REQUEST_URI']);
        new Flash($this->twig);
        new ConfirmMail($this->twig);
    }
}
