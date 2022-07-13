<?php

namespace Application\Controllers;

use Application\Services\CommentService;

class CommentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->commentService = new CommentService();
    }

    public function addComment(string $post, array $input)
    {
        $this->commentService->addComment($post, $input);
        header('Location: index.php?action=post&id=' . $post.'&flush=commentSubmitted');
    }

}
