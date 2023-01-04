<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\PostService;

class AdminPostController extends AdminController
{
    private $postService;

    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
    }

    public function index($category = "all", $nbr_show=5)
    {
        $option = "";
        $optionsData = [];
        if ($category === "NC") {
            $option = "WHERE category IS NULL";
        } elseif ($category !== "all") {
            $option = "WHERE category = ?";
            $optionsData = [$category];
        }
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
        echo 'done';
    }

    public function delete_picture($identifier)
    {
        $this->postService->delete_post_picture($identifier);
        echo 'done';
    }
}
