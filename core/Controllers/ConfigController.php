<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Controllers;

use Core\Services\FlashService;
use Core\Services\ConfigService;
use Core\Services\Encryption;

/**
 * ConfigController
 *
 * Manage the configuration parameters
 *
 * @category Core
 * @package  Core\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
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
     * Index
     *
     * Display the configuration parameters
     *
     * @return void
     */
    public function index()
    {
        $this->twig->display('admin/config/index.twig');
    }

    /**
     * List
     *
     * It gets a list of parameters from the config service,
     * and then displays them in a twig template
     *
     * @param string $prefix The prefix of the parameters to list.
     *
     * @return void
     */
    public function list($prefix)
    {
        $params = $this->configService->getSortedParameters($prefix);
        $this->twig->display('admin/config/list.twig', $params);
    }

    /**
     * Update
     *
     * The function updates a config value in the database
     *
     * @param string $id the id of the config item to update
     *
     * @return void
     */
    public function update($id)
    {
        if ($this->isPost()) {
            $prefix = $this->superglobals->getPrefix('name');
            if ($prefix == "mb_" || $prefix == "sd_") {
                $this->superglobals->setPost(
                    "value",
                    Encryption::encrypt(trim($this->superglobals->getPost('value')))
                );
            }
            $this->configService->update($id, $this->superglobals->getPost());
            if ($prefix == "af_") {
                if (
                    explode(
                        '_',
                        $this->superglobals->getPost('name')
                    )[1] == "color"
                ) {
                    $this->configService->renderColor($this->twig);
                }
            }
            if ($this->superglobals->getGet('anchor') != "") {
                $this->superglobals->setCookie(
                    'anchor',
                    $this->superglobals->getGet('anchor')
                );
            }
            $this->session->redirectLastUrl();
        }
        $params = $this->configService->get($id);
        $this->twig->display('admin/config/edit.twig', $params);
    }

    /**
     * Delete Value
     *
     * It deletes a value from the database
     *
     * @param string $identifier The identifier of the configuration value to delete.
     *
     * @return void
     */
    public function deleteValue($identifier)
    {
        $this->configService->deleteValue($identifier);
        echo 'done';
    }
}
