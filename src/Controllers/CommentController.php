<?php

namespace Application\Controllers;

use Core\Controllers\Controller;
use Application\Services\CommentService;
use Core\Middleware\Session\UserSession;

class CommentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->commentService = new CommentService();
    }

    public function add(string $post)
    {
        $userSession = new UserSession();
        $this->commentService->add(
            $_POST, 
            [
                'post' =>$post,
                'author' => $userSession->getUserParam("identifier")
            ],
            'Votre commentaire sera publié après <strong style="color:#f00;">validation</strong> par un administrateur.'
        );
        $this->superglobals->redirect('post', ['id' => $post], "commentList");
    }

    public function update(string $identifier)
    {
        if ($this->isPost()) {
            $this->commentService->update(
                $identifier, 
                $_POST, 
                'Votre commentaire sera à nouveau <strong style="color:#f00;">soumis à la modération</strong> et publié'
            );
            $this->session->redirectLastUrl('commentList');
        }
        $params = $this->commentService->get($identifier);
        $this->twig->display('post/update_comment.twig', $params);
    }

    // AJAX
    public function delete()
    {
        if ($this->isPost()) {
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
        if ($this->isPost()) {
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
