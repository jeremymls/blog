<?php

namespace Core\Controllers;

use Core\Middleware\ConfirmMail;
use Core\Middleware\Flash;
use Core\Services\ParamService;
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
        $this->twig = self::getTwig();
        new Flash($this->twig);
        new ConfirmMail($this->twig);
        $this->params = new ParamService();
        $this->twig->addGlobal('site_name', $this->params->get("site_name"));
    }

    private static function getTwig()
    {
        $loader = new FilesystemLoader(ROOT . '/templates');
        $twig = new Environment($loader, [
            'debug' => true,
        ]);
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new StringExtension());
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('get', $_GET);
        $twig->addGlobal('url_request', $_SERVER['REQUEST_URI']);
        return $twig;
    }
}
