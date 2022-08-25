<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\CommentService;

class AdminCommentController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->commentService = new CommentService();
    }

    public function index($filter = "pending")
    {
        $params = $this->commentService->getCommentsForBo($filter);
        $this->twig->display('admin/comment/index.twig', $params);
    }

    public function moderate(string $action, string $identifier)
    {
        $this->commentService->moderate($action, $identifier);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function delete(string $identifier)
    {
        $this->commentService->delete($identifier);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function multiple_moderation()
    {
        $this->commentService->multiple_moderation($_POST);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}

