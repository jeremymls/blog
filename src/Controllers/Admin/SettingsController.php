<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\UserService;
use Core\Services\ParamService;

class SettingsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->paramService = new ParamService();
        $this->userService = new UserService();
    }

    public function index()
    {
        $params['missing_params'] = $this->paramService->compareParams();
        $params['params'] = (array) $this->paramService->getParams();
        $this->twig->display('admin/config/index.twig', $params);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->paramService->update($id, $_POST);
            header('Location: /admin/settings');
        }
        $params["param"] = $this->paramService->getParam($id);
        $this->twig->display('admin/config/edit.twig', $params);
    }

    public function init()
    {
        $this->paramService->initParams();
        header('Location: /admin/settings');
    }
}
