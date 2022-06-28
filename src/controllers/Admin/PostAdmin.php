<?php

namespace Application\Controllers\Admin;

use Application\Controllers\Controller;
use Application\Lib\DatabaseConnection;
use Application\Model\CommentRepository;
use Application\Model\PostRepository;

class PostAdmin extends Controller
{
    public function index()
    {
        $postRepository = new PostRepository();
        $postRepository->connection = new DatabaseConnection();
        $posts = $postRepository->getPosts();

        $this->twig->display('admin/post/index.twig', [
            'posts' => $posts,
        ]);
    }

    public function add(?array $input)
    {
        // It handles the form submission when there is an input.
        if ($input !== null) {
            $title = null;
            $content = null;
            if (!empty($input['title']) && !empty($input['content'])) {
                $title = $input['title'];
                $content = $input['content'];
            } else {
                throw new \Exception('Les données du formulaire sont invalides.');
            }

            $postRepository = new PostRepository();
            $postRepository->connection = new DatabaseConnection();
            $success = $postRepository->add($title, $content);
            if (!$success) {
                throw new \Exception('Impossible de modifier le commentaire !');
            } else {
                header('Location: index.php?action=postAdmin');
            }
        }

        $this->twig->display('admin/post/action.twig', [
            'action' => 'add',
        ]);
    }

    public function update(string $identifier, ?array $input)
    {
        // It handles the form submission when there is an input.
        if ($input !== null) {
            $title = null;
            $content = null;
            if (!empty($input['title']) && !empty($input['content'])) {
                $title = $input['title'];
                $content = $input['content'];
            } else {
                throw new \Exception('Les données du formulaire sont invalides.');
            }

            $postRepository = new PostRepository();
            $postRepository->connection = new DatabaseConnection();
            $success = $postRepository->update($identifier, $title, $content);
            if (!$success) {
                throw new \Exception('Impossible de modifier le commentaire !');
            } else {
                header('Location: index.php?action=postAdmin');
            }
        }

        // Otherwise, it displays the form.
        $postRepository = new PostRepository();
        $postRepository->connection = new DatabaseConnection();
        $post = $postRepository->getPost($identifier);
        if ($post === null) {
            throw new \Exception("Le commentaire $identifier n'existe pas.");
        }

        $this->twig->display('admin/post/action.twig', [
            'post' => $post,
        ]);
    }

    public function delete(string $identifier)
    {
        $postRepository = new PostRepository();
        $postRepository->connection = new DatabaseConnection();
        $success = $postRepository->delete($identifier);
        if (!$success) {
            throw new \Exception('Impossible de supprimer le projet !');
        }
        header('Location: index.php?action=postAdmin&flush=success');
    }
}
