<?php

namespace Core;

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
        // flash messages
        $flash = null;
        if (isset($_COOKIE['flash'])) {
            $flash = $_COOKIE['flash'];
            $this->twig->addGlobal('type', $_COOKIE['type']);
            $this->twig->addGlobal('title', $_COOKIE['title']);
            $this->twig->addGlobal('message', $_COOKIE['message']);
        }
        $this->twig->addGlobal('flash', $flash);
    }
}
