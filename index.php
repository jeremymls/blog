<?php
if(! isset($_SESSION)){
    session_start();
}
require_once 'vendor/autoload.php';

define('ROOT', __DIR__);

use Application\Controllers\Admin\AdminCommentController;
use Application\Controllers\Admin\AdminPostController;
use Application\Controllers\Admin\DashboardController;
use Application\Controllers\CommentController;
use Application\Controllers\ErrorExceptionController;
use Application\Controllers\HomeController;
use Application\Controllers\PostController;
use Application\Controllers\Security\UserController;

try {
    if (isset($_GET['action']) && $_GET['action'] !== '') {

        // show posts
        if ($_GET['action'] === 'posts') {
            (new PostController())->index();
        // show post
        } elseif ($_GET['action'] === 'post') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                if (isset($_GET['flush'])) {
                    (new PostController())->show($identifier, $_GET['flush']);
                } else {
                    (new PostController())->show($identifier);
                }
            } else {
                throw new Exception('Aucun identifiant de billet envoyé');
            }

            // Register user
        } elseif ($_GET['action'] === 'register') {
            $input = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = $_POST;
            }
            (new UserController())->action('register', $input);
        // Profil user
        } elseif ($_GET['action'] === 'profil') {
                (new UserController())->show();
        // Login form
        } elseif ($_GET['action'] === 'login') {
            $input = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = $_POST;
            }
            (new UserController())->login($input);
        // Update user
        } elseif ($_GET['action'] === 'updateUser') {
            // if (isset($_GET['id']) && $_GET['id'] > 0) {  $_session
            $input = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = $_POST;
            }
            (new UserController())->action('edit', $input);
            // } else {
            //     throw new Exception('Aucun identifiant de profil envoyé');
            // }
        // Logout
        } elseif ($_GET['action'] === 'logout') {
            (new UserController())->logout();

        // Dashboard
        } elseif ($_GET['action'] === 'dashboard') {
                (new DashboardController())->execute();

        // Add comment
        } elseif ($_GET['action'] === 'addComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new CommentController())->addComment($identifier, $_POST);
            } else {
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        // Update comment
        } elseif ($_GET['action'] === 'updateComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                $input = null;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $input = $_POST;
                }
                (new AdminCommentController())->update($identifier, $input);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }
        // Validate comment
        } elseif ($_GET['action'] === 'validateComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new AdminCommentController())->validate($identifier);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }
        // Delete comment
        } elseif ($_GET['action'] === 'deleteComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new AdminCommentController())->delete($identifier);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }

        // Add post
        } elseif ($_GET['action'] === 'postAdd') {
            // It sets the input only when the HTTP method is POST (ie. the form is submitted).
            $input = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = $_POST;
            }
            (new AdminPostController())->add($input);
        // Post admin
        } elseif ($_GET['action'] === 'postAdmin') {
            (new AdminPostController())->index();
        // Comment admin
        } elseif ($_GET['action'] === 'commentAdmin') {
            if (isset($_GET['filter'])) {
                $filter = $_GET['filter'];
            } else {
                $filter = "unmoderated";
            }
            (new AdminCommentController())->index($filter);
        // show comment admin
        } elseif ($_GET['action'] === 'commentShowAdmin') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new AdminCommentController())->show($identifier);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }
        // delete post
        } elseif ($_GET['action'] === 'postDelete') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new AdminPostController())->delete($identifier);
            }
        // Update post
        } elseif ($_GET['action'] === 'postUpdate') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                $input = null;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $input = $_POST;
                }
                (new AdminPostController())->update($identifier, $input);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }

        // Error 404
        } else {
            throw new Exception("La page que vous recherchez n'existe pas.", 404);
        }

    } else {
        // show homepage
        (new HomeController())->execute();
    }
    // If the exception is thrown, it displays the error page.
} catch (Exception $err) {
    (new ErrorExceptionController())->execute($err);
}
