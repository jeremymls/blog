<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Services;

use Application\Models\Comment;
use Core\Services\EntityService;
use stdClass;

/**
 * CommentService
 *
 * Comment Service
 *
 * @category Application
 * @package  Application\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class CommentService extends EntityService
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Comment();
    }

    /**
     * Get Comments Filtered
     *
     * Get comments filtered by status
     *
     * @param mixed $filter Filter
     *
     * @return array
     */
    public function getCommentsFiltered($filter)
    {
        $option = "";
        switch ($filter) {
            case "all":
                $option = "";
                break;
            case "pending":
                $option = " WHERE moderate = 0";
                break;
            case "approved":
                $option = " WHERE moderate = 1";
                break;
            case "rejected":
                $option = " WHERE moderate = 2";
                break;
        }
        $params = $this->getAll($option);
        $params['comments'] = array_filter(
            $params['comments'],
            function ($comment) {
                return $comment->author->role != 'admin';
            }
        );
        if (!$params) {
            throw new \Exception('Impossible de récupérer les commentaires !');
        }
        return $params;
    }

    /**
     * Moderate
     *
     * Moderate a comment
     *
     * @param string $action     Action
     * @param string $identifier Identifier
     * @param bool   $showFlash  Show Flash
     * @param mixed  $csrf_token CSRF Token
     *
     * @return void
     */
    public function moderate(
        $action,
        $identifier,
        $showFlash = true,
        $csrf_token = null
    ) {
        switch ($action) {
            case '0':
                $flashMsg = 'La modération du commentaire a été annulée';
                break;
            case '1':
                $flashMsg = 'Le commentaire a été accepté';
                break;
            case '2':
                $flashMsg = 'Le commentaire a été refusé';
                break;
        }
        $entity = new stdClass();
        $entity->moderate = $action;
        $entity->csrf_token = $csrf_token
        ?? $this->superglobals->getGet("csrf_token");
        $this->update($identifier, $entity, $flashMsg, false, $showFlash);
    }

    /**
     * Multiple Moderation
     *
     * Moderate multiple comments
     *
     * @param mixed $input Input
     *
     * @return void
     */
    public function multipleModeration($input)
    {
        switch ($input['btnSubmit']) {
            case 'Invalider':
                $action = "0";
                $flash = 'La modération des commentaires a été annulée';
                break;
            case 'Valider':
                $action = "1";
                $flash = 'Les commentaires ont bien été validés';
                break;
            case 'Refuser':
                $action = "2";
                $flash = 'Les commentaires ont bien été refusés';
                break;
        }
        foreach ($input['comment'] as $identifier) {
            $this->moderate($action, $identifier, false, $input['csrf_token']);
        }
        $this->flashServices->success("Commentaires modifiés", $flash);
    }
}
