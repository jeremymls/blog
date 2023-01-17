<?php

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\CommentService;

/**
 * AdminCommentController
 * 
 * Admin Comment Controller
 */
class AdminCommentController extends AdminController
{
    private $commentService;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->commentService = new CommentService();
    }

    /**
     * index
     * 
     * Display the comments moderation list
     *
     * @param  string $filter
     * @param  mixed $nbr_show
     */
    public function index($filter = "pending", $nbr_show = 10)
    {
        $params = $this->commentService->getCommentsFiltered($filter);
        $params = $this->pagination->paginate($params, 'comments', $nbr_show );
        $params['filter'] = $filter;
        $this->twig->display('admin/comment/index.twig', $params);
    }

    /**
     * moderate
     * 
     * Moderate a comment
     *
     * @param  string $action
     * @param  string $identifier
     */
    public function moderate(string $action, string $identifier)
    {
        $this->commentService->moderate($action, $identifier);
        $this->superglobals->redirectLastUrl();
    }

    /**
     * delete
     * 
     * Delete a comment
     *
     * @param  string $identifier
     */
    public function delete(string $identifier)
    {
        $this->commentService->delete($identifier);
        $this->superglobals->redirectLastUrl();
    }

    /**
     * multiple_moderation
     * 
     * Multiple moderation
     */
    public function multiple_moderation()
    {
        $this->commentService->multiple_moderation($this->superglobals->getPost());
        $this->superglobals->redirectLastUrl();
    }
}

