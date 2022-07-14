<?php

namespace Application\Controllers\Admin;

use Application\Controllers\Controller;
use Application\Services\CommentService;

class AdminCommentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->commentService = new CommentService();
    }

    public function index($filter = "unmoderated")
    {
        $params = $this->commentService->getComments($filter);
        $this->twig->display('admin/comment/index.twig', $params);
    }

    public function show(string $identifier)
    {
        $params = $this->commentService->getCommentsForModeration($identifier);
        $this->twig->display('admin/comment/show.twig', $params);
    }

    public function validate(string $identifier)
    {
        $this->commentService->validateComment($identifier);
        header('Location: index.php?action=commentAdmin#moderate');
    }

    public function delete(string $identifier)
    {
        $this->commentService->delete($identifier);
        header('Location: index.php?action=commentAdmin#moderate');
    }

    public function update(string $identifier, ?array $input)
    {
        if ($input !== null) {
            $this->commentService->update($identifier, $input);
            header('Location: index.php?action=updateComment&id=' . $identifier);
        }

        $params = $this->commentService->getCommentsForModeration($identifier);
        $this->twig->display('update_comment.twig', $params);
    }
}

