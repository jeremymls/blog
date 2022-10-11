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

    public function index($category = null)
    {
        $option = $category !== null ? "WHERE category = ?" : "";
        $optionsData = $category !== null ? [$category] : [];
        $params = $this->postService->getAll($option, $optionsData);
        $params = $this->pagination->paginate($params, 'posts', 5);
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
            $shm = shm_attach(SHM_HTTP_REFERER);
            if (shm_has_var($shm, 1)) {
                $referer = shm_get_var($shm, 1);
                shm_remove_var($shm, 1);
                header('Location: ' . $referer);
            } else {
                header('Location: /admin/posts');
            }
        }
        $params = $this->postService->get($identifier);
        $this->twig->display('admin/post/action.twig', $params);
    }

    public function delete(string $identifier)
    {
        $this->postService->delete($identifier);
        header('Location: /admin/posts');
    }
}
