<?php

namespace Application\Controllers;

use Core\Controller;
use Application\Services\PostService;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
    }

    public function execute()
    {
        $params = $this->postService->getPosts();
        $this->twig->display('homepage.twig', $params);
    }
}
