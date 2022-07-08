<?php

namespace Application\Controllers;

use Application\Lib\DatabaseConnection;
use Application\Repositories\CommentRepository;
use Application\Repositories\PostRepository;

class PostController extends Controller
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

    public function show(string $identifier, string $flush = NULL )
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
            'alert' => $flush,
        ]);
    }

    public function addComment(string $post, array $input)
    {
        $author = null;
        $comment = null;
        if (!empty($input['comment'])) {
            $comment = $input['comment'];
        } else {
            throw new \Exception('Les données du formulaire sont invalides.');
        }

        $commentRepository = new CommentRepository();
        $commentRepository->connection = new DatabaseConnection();
        $success = $commentRepository->addComment($post, $comment);
        if (!$success) {
            throw new \Exception('Impossible d\'ajouter le commentaire !');
        } else {
            header('Location: index.php?action=post&id=' . $post.'&flush=commentSubmitted');
        }
    }
}
