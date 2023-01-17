<?php

namespace Core\Controllers;

use Core\Services\FlashService;
use Core\Services\ConfigService;
use Core\Services\Encryption;

/**
 * ConfigController
 * 
 * Manage the configuration parameters
 */
class ConfigController extends AdminController
{
    protected $configService;
    protected $flashService;
    
    /**
     * __construct
     * 
     * Create a new ConfigService and a new FlashService
     */
    public function __construct()
    {
        parent::__construct();
        $this->configService = new ConfigService();
        $this->flashService = FlashService::getInstance();
    }
    
    /**
     * index
     * 
     * Display the configuration parameters
     */
    public function index()
    {
        $this->twig->display('admin/config/index.twig');
    }
    
    /**
     * list
     *
     * It gets a list of parameters from the config service, and then displays them in a twig template
     * 
     * @param string $prefix The prefix of the parameters to list.
     */
    public function list($prefix)
    {
        $params = $this->configService->getSortedParameters($prefix);
        $this->twig->display('admin/config/list.twig', $params);
    }

    /**
     * update
     *
     * The function updates a config value in the database
     * 
     * @param string $id the id of the config item to update
     */
    public function update($id)
    {
        if ($this->isPost()) {
            $prefix = $this->superglobals->getPrefix('name');
            if ($prefix == "mb_" || $prefix == "sd_"){
                $this->superglobals->setPost("value", Encryption::encrypt(trim($this->superglobals->getPost('value'))));
            }
            $this->configService->update($id, $this->superglobals->getPost());
            if ($prefix == "af_"){
                if (explode('_', $this->superglobals->getPost('name'))[1]== "color"){
                    $this->configService->renderColor($this->twig);
                }
            }
            if($this->superglobals->getGet('anchor') != ""){
                $this->superglobals->setCookie('anchor',$this->superglobals->getGet('anchor'));
            }
            $this->session->redirectLastUrl();
        }
        $params = $this->configService->get($id);
        $this->twig->display('admin/config/edit.twig', $params);
    }

    /**
     * delete_value
     * 
     * It deletes a value from the database
     *
     * @param string $identifier The identifier of the configuration value to delete.
     */
    public function delete_value($identifier)
    {
        $this->configService->delete_value($identifier);
        echo 'done';
    }
}
