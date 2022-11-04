<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\PostService;

class AdminPostController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
    }

    public function index($category = "all", $nbr_show=5)
    {
        $option = $category !== "all" ? "WHERE category = ?" : "";
        $optionsData = $category !== "all" ? [$category] : [];
        $params = $this->postService->getAll($option, $optionsData);
        $params = $this->pagination->paginate($params, 'posts', $nbr_show);
        $params['categoryId'] = $category ?? "all";
        $this->twig->display('admin/post/index.twig', $params);
    }

    public function add()
    {
        if ($this->isPost()) {
            $this->postService->add($this->superglobals->getPost());
            $this->superglobals->redirect('admin:posts');
        }
        $this->twig->display('admin/post/action.twig', ['action' => 'add',]);
    }

    public function update(string $identifier)
    {
        if ($this->isPost()) {
            $this->postService->update($identifier, $this->superglobals->getPost());
            $this->session->redirectLastUrl();
        }
        $params = $this->postService->get($identifier);
        $this->twig->display('admin/post/action.twig', $params);
    }

    public function delete(string $identifier)
    {
        $this->postService->delete($identifier);
        $this->superglobals->redirect('admin:posts');
    }

    public function delete_picture($identifier)
    {
        $this->postService->delete_post_picture($identifier);
        $this->superglobals->redirectLastUrl();
    }
}
