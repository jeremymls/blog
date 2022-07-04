<?php

namespace Application\Controllers;

use Application\Lib\DatabaseConnection;
use Application\Models\CommentRepository;
use Application\Models\PostRepository;

class Post extends Controller
{

    public function index()
    {
        $postRepository = new PostRepository();
        $postRepository->connection = new DatabaseConnection();
        $posts = $postRepository->getPosts();

        $this->twig->display('post/index.twig', [
            'posts' => $posts,
        ]);
    }

    public function show(string $identifier)
    {
        $connection = new DatabaseConnection();

        $postRepository = new PostRepository();
        $postRepository->connection = $connection;
        $post = $postRepository->getPost($identifier);

        $commentRepository = new CommentRepository();
        $commentRepository->connection = $connection;
        $comments = $commentRepository->getComments($identifier);

        $this->twig->display('post/show.twig', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }
}
