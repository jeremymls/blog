<?php

namespace Application\Controllers;

use Application\Services\CategoryService;
use Application\Services\CommentService;
use Core\Controllers\Controller;
use Application\Services\PostService;

class PostController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
        $this->commentService = new CommentService();
    }

    public function index($category = null)
    {
        $option = $category !== null ? "WHERE category = ?" : "";
        $optionsData = $category !== null ? [$category] : [];
        $categoryService = new CategoryService();
        $params = $this->multiParams([
            $this->postService->getAll($option, $optionsData),
            $categoryService->getBy('id = ?', [$category])
        ]);
        $params = $this->pagination->paginate($params, 'posts', 3);
        $this->twig->display('post/index.twig', $params);
    }

    public function show(string $identifier )
    {
        $params = $this->multiParams([
            $this->postService->get($identifier), 
            $this->commentService->getAll('WHERE post = :post_id and (moderate = 1 or author = 1)', ['post_id' => $identifier])
        ]);
        $params = $this->pagination->paginate($params, 'comments', 5);
        $this->twig->display('post/show.twig', $params);
    }

    public function categories()
    {
        $this->twig->display('post/categories.twig');
    }
}
