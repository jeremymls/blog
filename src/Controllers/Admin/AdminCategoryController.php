<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Controllers\Admin
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Controllers\Admin;

use Application\Services\CategoryService;
use Core\Controllers\AdminController;

/**
 * AdminCategoryController
 *
 * Admin Category Controller
 *
 * @category Application
 * @package  Application\Controllers\Admin
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class AdminCategoryController extends AdminController
{
    private $categoryService;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->categoryService = new CategoryService();
    }

    /**
     * Index
     *
     * Display the categories admin list
     *
     * @return void
     */
    public function index()
    {
        $this->twig->display('admin/category/index.twig');
    }

    /**
     * Add
     *
     * Add category form
     *
     * @return void
     */
    public function add()
    {
        if ($this->isPost()) {
            $this->categoryService->add($this->superglobals->getPost());
            $this->superglobals->redirect('admin:categories');
        }
        $this->twig->display('admin/category/action.twig', ['action' => 'add',]);
    }

    /**
     * Update
     *
     * Update category form
     *
     * @param mixed $identifier the category identifier
     *
     * @return void
     */
    public function update(string $identifier)
    {
        if ($this->isPost()) {
            $this->categoryService->update(
                $identifier,
                $this->superglobals->getPost()
            );
            $this->superglobals->redirect('admin:categories');
        }
        $params = $this->categoryService->get($identifier);
        $this->twig->display('admin/category/action.twig', $params);
    }

    /**
     * Delete
     *
     * Delete category in AJAX
     *
     * @param mixed $identifier the category identifier
     *
     * @return void
     */
    public function delete(string $identifier)
    {
        $this->categoryService->delete($identifier);
        echo 'done';
    }
}
