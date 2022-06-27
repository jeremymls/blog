<?php

namespace Application\Controllers\Admin;

use Application\Controllers\Controller;
use Application\Lib\DatabaseConnection;
use Application\Model\CommentRepository;
use Application\Model\PostRepository;

class PostAdmin extends Controller
{
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
            $success = $postRepository->addPost($title, $content);
            if (!$success) {
                throw new \Exception('Impossible de modifier le commentaire !');
            } else {
                header('Location: index.php?action=dashboard');
            }
        }

        $this->twig->display('admin/post/add_post.twig', []);
    }
}
