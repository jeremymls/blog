<?php

namespace Application\Controllers;

use Application\Lib\DatabaseConnection;
use Application\Repositories\PostRepository;

class HomeController extends Controller
{
    public function execute()
    {
        $postRepository = new PostRepository();
        $postRepository->connection = new DatabaseConnection();
        $posts = $postRepository->getPosts();

        $this->twig->display('homepage.twig', [
            'posts' => $posts,
        ]);
    }
}
