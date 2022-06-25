<?php

namespace Application\Controllers;

use Application\Lib\DatabaseConnection;
use Application\Model\PostRepository;

class Homepage extends Controller
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
