<?php

use Core\Router\Router;

if (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    $url = '/';
}
$router = new Router($url);

// FRONTEND //

// Home
$router->get('/', 'Home@execute');
$router->post('/', 'Home@send');

// Post
$router->get('posts', 'Post@index');
$router->get('post/:id', 'Post@show');

// Comment
$router->post('post/:id', 'Comment@add');
$router->post('comment/delete', 'Comment@delete');
// $router->get('comment/delete', 'Comment@delete');
$router->post('comment/cancelDelete', 'Comment@cancelDelete');
$router->get('comment/:id', 'Comment@update');
$router->post('comment/:id', 'Comment@update');

// SECURITY //

// User
$router->post('profil/ajax/checkUsername', 'Security\User@checkUsername');
$router->get('profil', 'Security\User@show');
$router->get('profil/edit', 'Security\User@update');
$router->post('profil/edit', 'Security\User@update');
$router->get('profil/edit/mail', 'Security\User@edit_mail');
$router->post('profil/edit/mail', 'Security\User@edit_mail');
$router->get('profil/edit/password', 'Security\User@edit_password');
$router->post('profil/edit/password', 'Security\User@edit_password');
$router->get('profil/delete/image', 'Security\User@delete_picture');
$router->get('profil/register', 'Security\User@register');
$router->post('profil/register', 'Security\User@register');
$router->get('profil/:id', 'Security\User@show');
$router->get('login', 'Security\User@login');
$router->post('login', 'Security\User@login');
$router->get('logout', 'Security\User@logout');
$router->get('forget', 'Security\User@forget_password');
$router->post('forget', 'Security\User@forget_password');
$router->get('reset_password/:token', 'Security\User@reset_password');
$router->post('reset_password/:token', 'Security\User@reset_password');
$router->get('confirm_again', 'Security\User@confirm_again');

// Email Confirmation
$router->get('confirmation/:token', 'Security\User@confirmation');

// BACKEND //

// Dashboard
$router->get('dashboard', 'Admin\Dashboard@execute');

// AdminPost
$router->get('admin/posts', 'Admin\AdminPost@index');
$router->get('admin/posts/add', 'Admin\AdminPost@add');
$router->post('admin/posts/add', 'Admin\AdminPost@add');
$router->get('admin/posts/update/:id', 'Admin\AdminPost@update');
$router->post('admin/posts/update/:id', 'Admin\AdminPost@update');
$router->get('admin/posts/delete/:id', 'Admin\AdminPost@delete');

// AdminComment
$router->get('admin/comments/moderate/:action/:id', 'Admin\AdminComment@moderate');
$router->get('admin/comments/delete/:id', 'Admin\AdminComment@delete');
$router->get('admin/comments/:filter', 'Admin\AdminComment@index');
$router->post('admin/comments/:filter', 'Admin\AdminComment@multiple_moderation');
$router->post('admin/comments', 'Admin\AdminComment@multiple_moderation');
$router->get('admin/comments', 'Admin\AdminComment@index');

// AdminUser
$router->get('admin/users', 'Admin\AdminUser@index');
$router->get('admin/users/delete/:id', 'Admin\AdminUser@delete');

// AdminConfig
$router->get('admin/config/update/:name', 'Config@update@core');
$router->post('admin/config/update/:name', 'Config@update@core');
$router->get('admin/configs/list/:prefix', 'Config@list@core');
$router->get('admin/configs', 'Config@index@core');

// Core //

// Init
$router->get('init/missing_configs', 'Config@init_missing_configs@core');
$router->get('init/configs', 'Config@init_configs@core');
$router->get('init/tables', 'Config@init_tables@core');
$router->get('init', 'Init@init@core');
$router->get('new', 'Init@new@core');
$router->get('create_bdd', 'Init@create@core');

$router->run();
