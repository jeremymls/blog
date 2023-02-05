<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Controllers;

use Core\Controllers\Controller;
use Application\Services\CommentService;
use Core\Middleware\Session\UserSession;

/**
 * CommentController
 *
 * Comment Controller
 *
 * @category Application
 * @package  Application\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class CommentController extends Controller
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
     * Add
     *
     * Add a comment
     *
     * @param string $post the post identifier
     *
     * @return void
     */
    public function add(string $post)
    {
        $this->commentService->add(
            $this->superglobals->getPost(),
            [
                'post' => $post,
                'author' => UserSession::getInstance()->getUserParam("identifier")
            ],
            'Votre commentaire sera publié après
            <strong style="color:#f00;">validation</strong> par un administrateur.'
        );
        $this->superglobals->redirect('post', ['id' => $post], "commentList");
    }

    /**
     * Update
     *
     * Update a comment
     *
     * @param string $identifier the comment identifier
     *
     * @return void
     */
    public function update(string $identifier)
    {
        if ($this->isPost()) {
            $this->superglobals->setPost('moderate', 0);
            $this->commentService->update(
                $identifier,
                $this->superglobals->getPost(),
                'Votre commentaire sera à nouveau
                <strong style="color:#f00;">
                    soumis à la modération
                </strong> et publié'
            );
            $this->session->redirectLastUrl('commentList');
        }
        $params = $this->commentService->get($identifier);
        $this->twig->display('post/update_comment.twig', $params);
    }

    /**
     * Delete
     *
     * Soft delete a comment (AJAX)
     *
     * @param string $delete the comment identifier
     *
     * @return void
     */
    public function ajax($delete)
    {
        if ($this->isPost()) {
            $id = $this->superglobals->getPost('commentId');
            if ($id) {
                $this->commentService->deleteAjax($id, $delete);
                echo 'done';
            } else {
                throw new \Exception("Identifiant de commentaire manquant");
            }
        } else {
            throw new \Exception('Méthode non autorisée', 405);
        }
    }
}
