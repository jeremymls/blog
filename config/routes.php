<?php

use Application\Controllers\Admin\AdminCommentController;
use Application\Controllers\Admin\AdminPostController;
use Application\Controllers\Admin\AdminUserController;
use Application\Controllers\Admin\DashboardController;
use Application\Controllers\CommentController;
use Application\Controllers\PostController;
use Application\Controllers\Security\UserController;

/**
 * Déclaration des routes
 * 'action' => [                   action to execute
 *   'ctrl' => Controller::class,  controller class
 *   'fx' => 'function',           function name
 *   'opt' => 'option'             parameter
 * ]
 * @var array
 */
$routes = array(
  ## FRONTEND ##
  #Post
  'posts' => ['ctrl' => PostController::class, 'fx' => 'index'],                        // show posts
  'post' => ['ctrl' => PostController::class, 'fx' => 'show'],                          // show post (method -> GET:id) //todo: option flush
  #Comment
  'commentAdd' => ['ctrl' => CommentController::class, 'fx' => 'add'],                  // add comment (methods-> GET:id, POST:input)
  'commentUpdate' => ['ctrl' => CommentController::class, 'fx' => 'update'],            // update comment (methods-> GET:id, POST:input)
  ## SECURITY ##
  #User
  'profil' => ['ctrl' => UserController::class, 'fx' => 'show'],                        // show profil
  'register' => ['ctrl' => UserController::class, 'fx' => 'action', 'opt'=>'register'], // register
  'updateUser' => ['ctrl' => UserController::class, 'fx' => 'action', 'opt'=>'edit'],   // edit profil
  'login' => ['ctrl' => UserController::class, 'fx' => 'login'],                        // login (methods-> POST:input)
  'logout' => ['ctrl' => UserController::class, 'fx' => 'logout'],                      // logout
  ## BACKEND ##
  #Dashboard
  'dashboard' => ['ctrl' => DashboardController::class, 'fx' => 'execute'],             // dashboard
  #AdminPost
  'postAdmin' => ['ctrl' => AdminPostController::class, 'fx' => 'index'],               // show posts admin
  'postAdd' => ['ctrl' => AdminPostController::class, 'fx' => 'add'],                   // add post 
  'postUpdate' => ['ctrl' => AdminPostController::class, 'fx' => 'update'],             // update post (methods-> GET:id, POST:input)
  'postDelete' => ['ctrl' => AdminPostController::class, 'fx' => 'delete'],             // delete post (methods-> GET:id)
  #AdminComment
  'commentAdmin' => ['ctrl' => AdminCommentController::class, 'fx' => 'index'],         // comment admin
  'commentDelete' => ['ctrl' => AdminCommentController::class, 'fx' => 'delete'],       // delete comment (methods-> GET:id)
  'commentValidate' => ['ctrl' => AdminCommentController::class, 'fx' => 'validate'],   // validate comment (methods-> GET:id)
  #AdminUser
  'userAdmin' => ['ctrl' => AdminUserController::class, 'fx' => 'index'],               // user admin
  'userDelete' => ['ctrl' => AdminUserController::class, 'fx' => 'delete'],             // delete user (methods-> GET:id)
);