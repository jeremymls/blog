<?php

namespace Core\Controllers;

use Application\config\Routes;
use Application\Services\CategoryService;
use Core\Middleware\ConfirmMail;
use Core\Middleware\Flash;
use Core\Middleware\Pagination;
use Core\Middleware\Session\PHPSession;
use Core\Middleware\Session\UserSession;
use Core\Services\ConfigService;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;
require_once 'src/config/default.php';

abstract class Controller
{
    private $loader;
    protected $twig;

    public function __construct()
    {
        $this->pagination = new Pagination();
        $this->twig = self::getTwig();
        new ConfirmMail();
        new Flash($this->twig);
        self::getSiteConfigs();

    }

    private static function getTwig()
    {
        $loader = new FilesystemLoader(ROOT . '/templates');
        $twig = new Environment(
            $loader, [
            'debug' => true,
            ]
        );
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new StringExtension());
        $twig->addGlobal('get', $_GET);
        $twig->addGlobal('url_request', $_SERVER['REQUEST_URI']);
        $getUrlFunc = new \Twig\TwigFunction('getPath', function ($name) {
            $routes = new Routes();
            return $_SERVER["REQUEST_SCHEME"]. "://" . $_SERVER["HTTP_HOST"] . "/" .$routes->getUrl($name);
        });
        $twig->addFunction($getUrlFunc);
        // User Session functions
        $getUserParamFunc = new \Twig\TwigFunction('getUserParam', function ($param) {
            $userSession = new UserSession();
            return $userSession->getUserParam($param);
        });
        $twig->addFunction($getUserParamFunc);

        $isLoggedFunc = new \Twig\TwigFunction('isLogged', function () {
            $userSession = new UserSession();
            return $userSession->isUser();
        });
        $twig->addFunction($isLoggedFunc);

        $isAdminFunc = new \Twig\TwigFunction('isAdmin', function () {
            $userSession = new UserSession();
            return $userSession->isAdmin();
        });
        $twig->addFunction($isAdminFunc);

        $isValideFunc = new \Twig\TwigFunction('isValidate', function () {
            $userSession = new UserSession();
            return $userSession->isValidate();
        });
        $twig->addFunction($isValideFunc);

        return $twig;
    }

    private function getSiteConfigs()
    {
        if ((isset($_GET['url']) && !in_array($_GET['url'], ["/init", "/init/configs", "/init/tables", "/login", "/new", "/create_bdd","init/missing_configs", "/init/missing_configs", "init", "init/configs", "init/tables", "login", "new", "create_bdd"])) || !isset($_GET['url'])) {
            $configService = new ConfigService();
            if (count($configService->checkMissingConfigs()) > 0) {
            $session = new PHPSession();
                $session->set("safe_mode", true);
                header('Location: /init');
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
}
