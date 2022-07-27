<?php

namespace Application\Controllers;

use Core\Controller;
use Application\Services\CommentService;

class CommentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->commentService = new CommentService();
    }

    public function add(string $post, array $input)
    {
        $this->commentService->add($post, $input);
        header('Location: index.php?action=post&id=' . $post.'&flush=commentSubmitted');
    }

    public function update(string $identifier, array $input = null)
    {
        if ($input !== null) {
            $this->commentService->update($identifier, $input);
            header('Location: index.php?action=post&id=' . $input['post'].'&flush=commentPending');
        }
        $params = $this->commentService->getComment($identifier);
        $this->twig->display('post/update_comment.twig', $params);
    }
}
