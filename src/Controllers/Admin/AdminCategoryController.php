<?php

namespace Application\Controllers\Admin;

use Application\Services\CategoryService;
use Core\Controllers\AdminController;

/**
 * AdminCategoryController
 * 
 * Admin Category Controller
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
     * index
     * 
     * Display the categories admin list
     */
    public function index()
    {
        $this->twig->display('admin/category/index.twig');
    }

    /**
     * add
     * 
     * Add category form
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
     * update
     * 
     * Update category form
     *
     * @param  mixed $identifier
     */
    public function update(string $identifier)
    {
        if ($this->isPost()) {
            $this->categoryService->update($identifier, $this->superglobals->getPost());
            $this->superglobals->redirect('admin:categories');
        }
        $params = $this->categoryService->get($identifier);
        $this->twig->display('admin/category/action.twig', $params);
    }

    /**
     * delete
     * 
     * Delete category
     *
     * @param  mixed $identifier
     */
    public function delete(string $identifier)
    {
        $this->categoryService->delete($identifier);
        echo 'done';
    }
}
