<?php

require_once 'vendor/autoload.php';

define('ROOT', __DIR__);

use Application\Controllers\Admin\Dashboard;
use Application\Controllers\Admin\PostAdmin;
use Application\Controllers\Comment\AddComment;
use Application\Controllers\CommentAdmin;
use Application\Controllers\Homepage;
use Application\Controllers\Post;
use Application\Controllers\ErrorException;

try {
    if (isset($_GET['action']) && $_GET['action'] !== '') {
        if ($_GET['action'] === 'posts') {
                (new Post())->index();
        } elseif ($_GET['action'] === 'post') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];

                (new Post())->show($identifier);
            } else {
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        } elseif ($_GET['action'] === 'dashboard') {
            // if (isset($_GET['id']) && $_GET['id'] > 0) {
                // $identifier = $_GET['id'];

                (new Dashboard())->execute();
            // } else {
                // throw new Exception('Aucun identifiant de billet envoyé');
            // }
        } elseif ($_GET['action'] === 'addComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];

                (new AddComment())->execute($identifier, $_POST);
            } else {
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        } elseif ($_GET['action'] === 'updateComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                // It sets the input only when the HTTP method is POST (ie. the form is submitted).
                $input = null;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $input = $_POST;
                }

                (new CommentAdmin())->update($identifier, $input);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }
        } elseif ($_GET['action'] === 'postAdd') {
            // It sets the input only when the HTTP method is POST (ie. the form is submitted).
            $input = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = $_POST;
            }
            (new PostAdmin())->add($input);
        } elseif ($_GET['action'] === 'postAdmin') {
            (new PostAdmin())->index();
        } elseif ($_GET['action'] === 'postDelete') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                (new PostAdmin())->delete($identifier);
            }
        } elseif ($_GET['action'] === 'postUpdate') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $identifier = $_GET['id'];
                // It sets the input only when the HTTP method is POST (ie. the form is submitted).
                $input = null;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $input = $_POST;
                }

                (new PostAdmin())->update($identifier, $input);
            } else {
                throw new Exception('Aucun identifiant de commentaire envoyé');
            }

        } else {
            throw new Exception("La page que vous recherchez n'existe pas.", 404);
        }
    } else {
        (new Homepage())->execute();
    }
} catch (Exception $err) {
    (new ErrorException())->execute($err);
}
