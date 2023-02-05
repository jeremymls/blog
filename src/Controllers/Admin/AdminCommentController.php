<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Controllers\Admin
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Controllers\Admin;

use Core\Controllers\AdminController;
use Application\Services\CommentService;

/**
 * AdminCommentController
 *
 * Admin Comment Controller
 *
 * @category Application
 * @package  Application\Controllers\Admin
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
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
     * Index
     *
     * Display the comments moderation list
     *
     * @param string $filter   the filter
     * @param mixed  $nbr_show the number of comments to show
     *
     * @return void
     */
    public function index($filter = "pending", $nbr_show = 10)
    {
        $params = $this->commentService->getCommentsFiltered($filter);
        $params = $this->pagination->paginate($params, 'comments', $nbr_show);
        $params['filter'] = $filter;
        $this->twig->display('admin/comment/index.twig', $params);
    }

    /**
     * Moderate
     *
     * Moderate a comment
     *
     * @param string $action     the action
     * @param string $identifier the comment identifier
     *
     * @return void
     */
    public function moderate(string $action, string $identifier)
    {
        $this->commentService->moderate($action, $identifier);
        $this->superglobals->redirectLastUrl();
    }

    /**
     * Delete
     *
     * Delete a comment
     *
     * @param string $identifier the comment identifier
     *
     * @return void
     */
    public function delete(string $identifier)
    {
        $this->commentService->delete($identifier);
        $this->superglobals->redirectLastUrl();
    }

    /**
     * Multiple Moderation
     *
     * Multiple moderation
     *
     * @return void
     */
    public function multipleModeration()
    {
        $this->commentService->multipleModeration($this->superglobals->getPost());
        $this->superglobals->redirectLastUrl();
    }
}
