<?php

namespace Core;

use Core\Middleware\Flash;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;

abstract class Controller
{
    private $loader;
    protected $twig;

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(ROOT . '/templates');
        $this->twig = new \Twig\Environment($this->loader, [
            'debug' => true,
        ]);
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new StringExtension());
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('get', $_GET);
        $this->twig->addGlobal('url_request', $_SERVER['REQUEST_URI']);
        new Flash($this->twig);
    }
}
