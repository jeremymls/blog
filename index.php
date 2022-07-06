<?php
if(! isset($_SESSION)){
    session_start();
}
require_once 'vendor/autoload.php';

define('ROOT', __DIR__);

use Application\Controllers\Security\User;
use Application\Controllers\Admin\Dashboard;
use Application\Controllers\Admin\PostAdmin;
use Application\Controllers\Admin\CommentAdmin;
use Application\Controllers\Homepage;
use Application\Controllers\Post;
use Application\Controllers\ErrorException;

try {
    if (isset($_GET['action']) && $_GET['action'] !== '') {

        // show posts
        if ($_GET['action'] === 'posts') {
            (new Post())->index();
        // show post
        } elseif ($_GET['action'] === 'post') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                if (isset($_GET['flush'])) {
                    (new Post())->show($identifier, $_GET['flush']);
                } else {
                    (new Post())->show($identifier);
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
            (new User())->action('register', $input);
        // Profil user
        } elseif ($_GET['action'] === 'profil') {
                (new User())->show();
        // Login form
        } elseif ($_GET['action'] === 'login') {
            $input = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = $_POST;
            }
            (new User())->login($input);
        // Update user
        } elseif ($_GET['action'] === 'updateUser') {
            // if (isset($_GET['id']) && $_GET['id'] > 0) {  $_session
            $input = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = $_POST;
            }
            (new User())->action('edit', $input);
            // } else {
            //     throw new Exception('Aucun identifiant de profil envoyé');
            // }
        // Logout
        } elseif ($_GET['action'] === 'logout') {
            (new User())->logout();

        // Dashboard
        } elseif ($_GET['action'] === 'dashboard') {
                (new Dashboard())->execute();

        // Add comment
        } elseif ($_GET['action'] === 'addComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new Post())->addComment($identifier, $_POST);
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
                (new CommentAdmin())->update($identifier, $input);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }
        // Validate comment
        } elseif ($_GET['action'] === 'validateComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new CommentAdmin())->validate($identifier);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }
        // Delete comment
        } elseif ($_GET['action'] === 'deleteComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new CommentAdmin())->delete($identifier);
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
            (new PostAdmin())->add($input);
        // Post admin
        } elseif ($_GET['action'] === 'postAdmin') {
            (new PostAdmin())->index();
        // Comment admin
        } elseif ($_GET['action'] === 'commentAdmin') {
            (new CommentAdmin())->index();
        // show comment admin
        } elseif ($_GET['action'] === 'commentShowAdmin') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new CommentAdmin())->show($identifier);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }
        // delete post
        } elseif ($_GET['action'] === 'postDelete') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new PostAdmin())->delete($identifier);
            }
        // Update post
        } elseif ($_GET['action'] === 'postUpdate') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                $input = null;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $input = $_POST;
                }
                (new PostAdmin())->update($identifier, $input);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }

        // Error 404
        } else {
            throw new Exception("La page que vous recherchez n'existe pas.", 404);
        }

    } else {
        // show homepage
        (new Homepage())->execute();
    }
    // If the exception is thrown, it displays the error page.
} catch (Exception $err) {
    (new ErrorException())->execute($err);
}
