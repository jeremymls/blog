<?php

namespace Application\Controllers\Admin;

use Application\Controllers\Controller;
use Application\Lib\DatabaseConnection;
use Application\Repositories\CommentRepository;
use Application\Repositories\PostRepository;


class CommentAdmin extends Controller
{
    public function index()
    {
        $commentRepository = new CommentRepository();
        $commentRepository->connection = new DatabaseConnection();
        $comments = $commentRepository->getUnmoderatedComments();

        // $postRepository = new PostRepository();
        // $postRepository->connection = new DatabaseConnection();
        // $posts = $postRepository->getPosts();

        // $commentsByProjects = [];
        // foreach ($posts as $post) {
        //     $commentsByProjects[$post->identifier]->title = $post->title;
        //     $commentsByProjects[$post->identifier]->comments =  $commentRepository->getUnmoderatedCommentsByProject($post->identifier);

            
        //     // $commentsByProjects[$post->title] = $commentRepository->getComments($post->title);
        // }

        $this->twig->display('admin/comment/index.twig', [
            'comments' => $comments,
            // 'commentsByProjects' => $commentsByProjects,
        ]);
    }

    public function show(string $identifier)
    {
        $commentRepository = new CommentRepository();
        $commentRepository->connection = new DatabaseConnection();
        $comment = $commentRepository->getComment($identifier);
        $comments = $commentRepository->getComments($comment->post);

        $this->twig->display('admin/comment/show.twig', [
            'comment' => $comment,
            'comments' => $comments,
        ]);
        
    }

    public function validate(string $identifier)
    {
    
        $commentRepository = new CommentRepository();
        $commentRepository->connection = new DatabaseConnection();
        $success = $commentRepository->validateComment($identifier);
        if (!$success) {
            throw new \Exception('Impossible de valider le commentaire !');
        } else {
            header('Location: index.php?action=commentAdmin#date');
        }

    }

    public function delete(string $identifier)
    {
    
        $commentRepository = new CommentRepository();
        $commentRepository->connection = new DatabaseConnection();
        $success = $commentRepository->deleteComment($identifier);
        if (!$success) {
            throw new \Exception('Impossible de supprimer le commentaire !');
        } else {
            header('Location: index.php?action=commentAdmin#date');
        }

    }

    public function update(string $identifier, ?array $input)
    {
        // It handles the form submission when there is an input.
        if ($input !== null) {
            $author = null;
            $comment = null;
            if (!empty($input['author']) && !empty($input['comment'])) {
                $author = $input['author'];
                $comment = $input['comment'];
            } else {
                throw new \Exception('Les données du formulaire sont invalides.');
            }

            $commentRepository = new CommentRepository();
            $commentRepository->connection = new DatabaseConnection();
            $success = $commentRepository->updateComment($identifier, $author, $comment);
            if (!$success) {
                throw new \Exception('Impossible de modifier le commentaire !');
            } else {
                header('Location: index.php?action=updateComment&id=' . $identifier);
            }
        }

        // Otherwise, it displays the form.
        $commentRepository = new CommentRepository();
        $commentRepository->connection = new DatabaseConnection();
        $comment = $commentRepository->getComment($identifier);
        if ($comment === null) {
            throw new \Exception("Le commentaire $identifier n'existe pas.");
        }

        $this->twig->display('update_comment.twig', [
            'comment' => $comment,
        ]);
    }
}

