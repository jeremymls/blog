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
        $params = $this->commentService->getCommentsFiltered($filter);
        $params = $this->pagination->paginate($params, 'comments', 10);
        $params['filter'] = $filter;
        $this->twig->display('admin/comment/index.twig', $params);
    }

    public function moderate(string $action, string $identifier)
    {
        $this->commentService->moderate($action, $identifier);
        $this->superglobals->redirectLastUrl();
    }

    public function delete(string $identifier)
    {
        $this->commentService->delete($identifier);
        $this->superglobals->redirectLastUrl();
    }

    public function multiple_moderation()
    {
        $this->commentService->multiple_moderation($_POST);
        $this->superglobals->redirectLastUrl();
    }
}

