<?php

namespace Application\Controllers;

use Core\Controllers\Controller;
use Application\Services\CommentService;

class CommentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->commentService = new CommentService();
    }

    public function add(string $post)
    {
        $this->commentService->add($_POST, [
            'post' =>$post,
            'author' => $_SESSION['user']->id,
        ]);
        header('Location: /post/' . $post . '#commentList');
    }

    public function update(string $identifier)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->commentService->update(
                $identifier, 
                $_POST, 
                'Votre commentaire sera à nouveau <strong style="color:#f00;">soumis à la modération</strong> et publié'
            );
            $shm = shm_attach(SHM_HTTP_REFERER);
            if (shm_has_var($shm, 1)) {
                $referer = shm_get_var($shm, 1);
                shm_remove_var($shm, 1);
                header('Location: ' . $referer);
            } else {
                header('Location: /');
            }
        }
        $params = $this->commentService->get($identifier);
        $this->twig->display('post/update_comment.twig', $params);
    }
}
