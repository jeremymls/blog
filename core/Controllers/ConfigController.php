<?php

namespace Core\Controllers;

use Core\Services\FlashService;
use Core\Services\ConfigService;
use Core\Services\Encryption;

class ConfigController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->configService = new ConfigService();
        $this->flashService = FlashService::getInstance();
    }

    public function index()
    {
        $this->twig->display('admin/config/index.twig');
    }

    public function list($prefix)
    {
        $params = $this->configService->getSortedParameters($prefix);
        $this->twig->display('admin/config/list.twig', $params);
    }

    public function update($id)
    {
        if ($this->isPost()) {
            $prefix = $this->superglobals->getPrefix('name');
            if ($prefix == "mb_" || $prefix == "sd_"){
                $this->superglobals->setPost("value", Encryption::encrypt(trim($this->superglobals->getPost('value'))));
            }
            $this->configService->update($id, $this->superglobals->getPost());
            $this->session->redirectLastUrl();
        }
        $params = $this->configService->get($id);
        $this->twig->display('admin/config/edit.twig', $params);
    }
}
