<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\CommentService;

class AdminCommentController extends AdminController
{
    private $commentService;

    public function __construct()
    {
        parent::__construct();
        $this->commentService = new CommentService();
    }

    public function index($filter = "pending", $nbr_show = 10)
    {
        $params = $this->commentService->getCommentsFiltered($filter);
        $params = $this->pagination->paginate($params, 'comments', $nbr_show );
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
        $this->commentService->multiple_moderation($this->superglobals->getPost());
        $this->superglobals->redirectLastUrl();
    }
}

