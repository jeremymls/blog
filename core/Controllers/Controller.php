<?php

namespace Core\Controllers;

use Core\Middleware\ConfirmMail;
use Core\Middleware\Flash;
use Core\Services\ConfigService;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;
include_once('src/config/default.php');

abstract class Controller
{
    private $loader;
    protected $twig;

    public function __construct()
    {
        $this->twig = self::getTwig();
        new Flash($this->twig);
        new ConfirmMail($this->twig);
        self::getConfigs();

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

    private function getConfigs()
    {
        if ((isset($_GET['url']) && !in_array($_GET['url'],["init","init/configs","init/tables","login","new","create_bdd"])) || !isset($_GET['url'])) {
            $configService = new ConfigService();
            if (count($configService->checkMissingConfigs()) > 0) {
                $_SESSION['safe_mode'] = true;
                header('Location: /init');
            }
            $configs = $configService->getConfigs();
            foreach ($configs as $config) {
                $prefix = explode("_", $config->name)[0];
                if ($prefix != "mb") {
                    $this->twig->addGlobal($config->name, $config->value);
                }
            }
        }
    }
}
