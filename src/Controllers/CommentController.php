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

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['commentId'])) {
                $deletion_confirmation = $this->commentService->delete_ajax($_POST['commentId']);
                if ($deletion_confirmation === true) {
                    echo true;
                } else {
                    echo false;
                }
            }
        } else {
            throw new \Exception('Méthode non autorisée', 405);
        }
    }

    public function cancelDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['commentId'])) {
                $this->commentService->moderate(0, $_POST['commentId'],false);
                $cancel_deletion_confirmation = $this->commentService->delete_ajax($_POST['commentId'], false);
                if ($cancel_deletion_confirmation === true) {
                    echo true;
                } else {
                    echo false;
                }
            }
        } else {
            throw new \Exception('Méthode non autorisée', 405);
        }
    }
}
