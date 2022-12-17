<?php

namespace Application\Controllers;

use Core\Controllers\Controller;
use Application\Services\CommentService;
use Core\Middleware\Session\UserSession;

class CommentController extends Controller
{
    private $commentService;

    public function __construct()
    {
        parent::__construct();
        $this->commentService = new CommentService();
    }

    public function add(string $post)
    {
        $this->commentService->add(
            $this->superglobals->getPost(), 
            [
                'post' =>$post,
                'author' => UserSession::getInstance()->getUserParam("identifier")
            ],
            'Votre commentaire sera publié après <strong style="color:#f00;">validation</strong> par un administrateur.'
        );
        $this->superglobals->redirect('post', ['id' => $post], "commentList");
    }

    public function update(string $identifier)
    {
        if ($this->isPost()) {
            $this->superglobals->setPost('moderate', 0);
            $this->commentService->update(
                $identifier, 
                $this->superglobals->getPost(), 
                'Votre commentaire sera à nouveau <strong style="color:#f00;">soumis à la modération</strong> et publié'
            );
            $this->session->redirectLastUrl('commentList');
        }
        $params = $this->commentService->get($identifier);
        $this->twig->display('post/update_comment.twig', $params);
    }

    // AJAX
    public function ajax($delete)
    {
        if ($this->isPost()) {
            $id = $this->superglobals->getPost('commentId');
            if ($id) {
                $this->commentService->delete_ajax($id, $delete);
                echo 'done';
            } else {
                throw new \Exception("Identifiant de commentaire manquant");
            }
        } else {
            throw new \Exception('Méthode non autorisée', 405);
        }
    }
}
