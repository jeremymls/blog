<?php

namespace Application\Controllers\Admin;

use Core\AdminController;
use Application\Services\PostService;

class AdminPostController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
    }

    public function index()
    {
        $params = $this->postService->getPosts();
        $this->twig->display('admin/post/index.twig', $params);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->postService->add($_POST);
            header('Location: /admin/posts');
        }
        $this->twig->display('admin/post/action.twig', ['action' => 'add',]);
    }

    public function update(string $identifier)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->postService->update($identifier, $_POST);
            header('Location: /admin/posts');
        }
        $params = $this->postService->getPost($identifier);
        $this->twig->display('admin/post/action.twig', $params);
    }

    public function delete(string $identifier)
    {
        $this->postService->delete($identifier);
        header('Location: /admin/posts');
    }
}
