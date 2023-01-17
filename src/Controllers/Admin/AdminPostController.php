<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\PostService;

/**
 * AdminPostController
 * 
 * Admin Post Controller
 */
class AdminPostController extends AdminController
{
    private $postService;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
    }

    /**
     * index
     * 
     * Display the posts admin list
     *
     * @param  mixed $category
     * @param  mixed $nbr_show
     */
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

    /**
     * add
     * 
     * Add a post
     */
    public function add()
    {
        if ($this->isPost()) {
            $this->postService->add($this->superglobals->getPost());
            $this->superglobals->redirect('admin:posts');
        }
        $this->twig->display('admin/post/action.twig', ['action' => 'add',]);
    }

    /**
     * update
     * 
     * Update a post
     *
     * @param  string $identifier
     */
    public function update(string $identifier)
    {
        if ($this->isPost()) {
            $this->postService->update($identifier, $this->superglobals->getPost());
            $this->session->redirectLastUrl();
        }
        $params = $this->postService->get($identifier);
        $this->twig->display('admin/post/action.twig', $params);
    }

    /**
     * delete
     * 
     * Delete a post
     *
     * @param  string $identifier
     */
    public function delete(string $identifier)
    {
        $this->postService->delete($identifier);
        echo 'done';
    }

    /**
     * delete_picture
     * 
     * Delete a post picture
     *
     * @param  string $identifier post identifier
     */
    public function delete_picture($identifier)
    {
        $this->postService->delete_post_picture($identifier);
        echo 'done';
    }
}
