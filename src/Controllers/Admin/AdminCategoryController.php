<?php

namespace Application\Controllers\Admin;

use Application\Services\CategoryService;
use Core\Controllers\AdminController;
use Application\Services\PostService;

class AdminCategoryController extends AdminController
{
    private $categoryService;

    public function __construct()
    {
        parent::__construct();
        $this->categoryService = new CategoryService();
    }

    public function index()
    {
        $this->twig->display('admin/category/index.twig');
    }

    public function add()
    {
        if ($this->isPost()) {
            $this->categoryService->add($this->superglobals->getPost());
            $this->superglobals->redirect('admin:categories');
        }
        $this->twig->display('admin/category/action.twig', ['action' => 'add',]);
    }

    public function update(string $identifier)
    {
        if ($this->isPost()) {
            $this->categoryService->update($identifier, $this->superglobals->getPost());
            $this->superglobals->redirect('admin:categories');
        }
        $params = $this->categoryService->get($identifier);
        $this->twig->display('admin/category/action.twig', $params);
    }

    public function delete(string $identifier)
    {
        $this->categoryService->delete($identifier);
        $this->superglobals->redirect('admin:categories');
    }
}
