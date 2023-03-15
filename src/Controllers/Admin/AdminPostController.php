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

use Core\Controllers\AdminController;
use Application\Services\PostService;

/**
 * AdminPostController
 *
 * Admin Post Controller
 *
 * @category Application
 * @package  Application\Controllers\Admin
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
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
     * Index
     *
     * Display the posts admin list
     *
     * @param mixed $category the category identifier
     * @param mixed $nbr_show the number of posts to show
     *
     * @return void
     */
    public function index($category = "all", $nbr_show = 5)
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
     * Add
     *
     * Add a post
     *
     * @return void
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
     * Update
     *
     * Update a post
     *
     * @param string $identifier post identifier
     *
     * @return void
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
     * Delete
     *
     * Delete a post
     *
     * @param string $identifier post identifier
     *
     * @return void
     */
    public function delete(string $identifier)
    {
        $this->postService->delete($identifier);
    }

    /**
     * Delete Picture
     *
     * Delete a post picture in AJAX
     *
     * @param string $identifier post identifier
     *
     * @return void
     */
    public function deletePicture($identifier)
    {
        $this->postService->deletePostPicture($identifier);
    }
}
