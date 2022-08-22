<?php

namespace Application\Controllers;

use Core\Controllers\Controller;
use Application\Services\PostService;

class PostController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
    }

    public function index()
    {
        $params = $this->postService->getPosts();
        $this->twig->display('post/index.twig', $params);
    }

    public function show(string $identifier )
    {
        $params = $this->postService->getPostWithComments($identifier);
        $this->twig->display('post/show.twig', $params);
    }

}
