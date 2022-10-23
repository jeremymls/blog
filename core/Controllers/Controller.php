<?php

namespace Core\Controllers;

use Application\Services\CategoryService;
use Core\Middleware\ConfirmMail;
use Core\Middleware\Flash;
use Core\Middleware\Pagination;
use Core\Middleware\Session\PHPSession;
use Core\Middleware\Session\UserSession;
use Core\Middleware\Superglobals;
use Core\Services\ConfigService;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;
require_once 'src/config/default.php';

abstract class Controller
{
    protected $twig;

    public function __construct()
    {
        $this->pagination = new Pagination();
        $this->superglobals = new Superglobals();
        $this->userSession = new UserSession();
        $this->twig = self::getTwig($this->superglobals, $this->userSession); // todo: verify if it's ok
        new ConfirmMail();
        new Flash($this->twig);
        self::getSiteConfigs($this->superglobals);
        $this->session = new PHPSession();
    }

    private static function getTwig(Superglobals $superglobals, UserSession $userSession)
    {
        $loader = new FilesystemLoader(ROOT . '/templates');
        $twig = new Environment(
            $loader, [
            'debug' => true,
            ]
        );
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new StringExtension());
        $twig->addGlobal('get', $superglobals->getGet());
        $twig->addGlobal('url_request', $superglobals->getPath());

        $getUrlFunc = new \Twig\TwigFunction('getPath', function ($name = null, $params = []) use ($superglobals) {
            return $superglobals->getPath($name, $params);
        });
        $twig->addFunction($getUrlFunc);

        $removeGetUrlFunc = new \Twig\TwigFunction('getPathWithoutGet', function () use ($superglobals) {
            return $superglobals->getPathWithoutGet();
        });
        $twig->addFunction($removeGetUrlFunc);

        // User Session functions
        $getUserParamFunc = new \Twig\TwigFunction('getUserParam', function ($param) use ($userSession) {
            return $userSession->getUserParam($param);
        });
        $twig->addFunction($getUserParamFunc);

        $isLoggedFunc = new \Twig\TwigFunction('isLogged', function () use ($userSession) {
            return $userSession->isUser();
        });
        $twig->addFunction($isLoggedFunc);

        $isAdminFunc = new \Twig\TwigFunction('isAdmin', function () use ($userSession) {
            return $userSession->isAdmin();
        });
        $twig->addFunction($isAdminFunc);

        $isValideFunc = new \Twig\TwigFunction('isValidate', function () use ($userSession) {
            return $userSession->isValidate();
        });
        $twig->addFunction($isValideFunc);

        $redirectFunc = new \Twig\TwigFunction('redirect', function ($name, $params = null) use ($superglobals) {
            return $superglobals->redirect($name, $params);
        });
        $twig->addFunction($redirectFunc);

        return $twig;
    }

    private function getSiteConfigs(Superglobals $superglobals)
    {
        $url = $superglobals->getPath();
        if (!in_array($url, [
            $superglobals->getPath("login"), 
            $superglobals->getPath("new"), 
            $superglobals->getPath("init"), 
            $superglobals->getPath("create_bdd"), 
            $superglobals->getPath("init:configs"), 
            $superglobals->getPath("init:tables"),
            $superglobals->getPath("init:missing_configs")
        ])) {
            $configService = new ConfigService();
            $session = new PHPSession();
            if (count($configService->checkMissingConfigs()) > 0) {
                $session->set("safe_mode", true);
                $this->superglobals->redirect('init');
            }else{
                $session->set("safe_mode", false);
            }
            $categoryService = new CategoryService();
            $params = $this->multiParams([
                $categoryService->getAll("", [], "", "", 'ASC'),
                $configService->getAll()
            ]);
            $this->twig->addGlobal('categories', $params['categories']);
            foreach ($params["configs"] as $config) {
                $prefix = explode("_", $config->name)[0];
                if ($prefix != "mb") {
                    $this->twig->addGlobal($config->name, $config->value);
                }
            }
        }
    }

    public function multiParams(array $params)
    {
        $params = array_merge(...$params);
        return $params;
    }

    public function isPost()
    {
        return $this->superglobals->getMethod() == 'POST';
    }
}
