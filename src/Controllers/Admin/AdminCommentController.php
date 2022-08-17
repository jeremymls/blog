<?php

namespace Application\Controllers\Admin;

use Core\AdminController;
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

    public function validate(string $identifier)
    {
        $this->commentService->commentValidate($identifier);
        header('Location: /admin/comments');
    }

    public function delete(string $identifier)
    {
        $this->commentService->delete($identifier);
        header('Location: /admin/comments');
    }
}

