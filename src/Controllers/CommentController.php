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
        $this->commentService->add($post, $_POST);
        header('Location: /post/' . $post);
    }

    public function update(string $identifier)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->commentService->update($identifier, $_POST);
            header('Location: /post/' . $_POST['post']);
        }
        $params = $this->commentService->getComment($identifier);
        $this->twig->display('post/update_comment.twig', $params);
    }
}
