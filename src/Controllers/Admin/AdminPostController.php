<?php

namespace Application\Controllers\Admin;

use Application\Controllers\Controller;
use Application\Services\PostService;

class AdminPostController extends Controller
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

    public function add(?array $input)
    {
        if ($input !== null) {
            $this->postService->add($input);
            header('Location: index.php?action=postAdmin');
        }
        $this->twig->display('admin/post/action.twig', ['action' => 'add',]);
    }

    public function update(string $identifier, ?array $input)
    {
        if ($input !== null) {
            $this->postService->update($identifier, $input);
            header('Location: index.php?action=postAdmin');
        }
        $params = $this->postService->getPost($identifier);
        $this->twig->display('admin/post/action.twig', $params);
    }

    public function delete(string $identifier)
    {
        $this->postService->delete($identifier);
        header('Location: index.php?action=postAdmin&flush=success');
    }
}
