<?php

use Core\Router;

if (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    $url = '/';
}
$router = new Router($url);

## FRONTEND ##

#Home
$router->get('/', 'Home@execute');

#Post
$router->get('posts', 'Post@index');
$router->get('post/:id', 'Post@show');

#Comment
$router->post('post/:id', 'Comment@add');
$router->get('comment/:id', 'Comment@update');
$router->post('comment/:id', 'Comment@update');

## SECURITY ##

#User
$router->get('profil', 'Security\User@show');
$router->get('profil/edit', 'Security\User@update');
$router->post('profil/edit', 'Security\User@update');
$router->get('profil/edit/mail', 'Security\User@edit_mail');
$router->post('profil/edit/mail', 'Security\User@edit_mail');
$router->get('profil/edit/password', 'Security\User@edit_password');
$router->post('profil/edit/password', 'Security\User@edit_password');
$router->get('profil/register', 'Security\User@register');
$router->post('profil/register', 'Security\User@register');
$router->get('profil/:id', 'Security\User@show');
$router->get('login', 'Security\User@login');
$router->post('login', 'Security\User@login');
$router->get('logout', 'Security\User@logout');

#Email Confirmation
$router->get('confirmation/:token', 'Security\User@confirmation');

## BACKEND ##

#Dashboard
$router->get('dashboard', 'Admin\Dashboard@execute');

#AdminPost
$router->get('admin/posts', 'Admin\AdminPost@index');
$router->get('admin/posts/add', 'Admin\AdminPost@add');
$router->post('admin/posts/add', 'Admin\AdminPost@add');
$router->get('admin/posts/update/:id', 'Admin\AdminPost@update');
$router->post('admin/posts/update/:id', 'Admin\AdminPost@update');
$router->get('admin/posts/delete/:id', 'Admin\AdminPost@delete');

#AdminComment
$router->get('admin/comments', 'Admin\AdminComment@index');
$router->get('admin/comments/:filter', 'Admin\AdminComment@index');
$router->get('admin/comments/delete/:id', 'Admin\AdminComment@delete');
$router->get('admin/comments/validate/:id', 'Admin\AdminComment@validate');

#AdminUser
$router->get('admin/users', 'Admin\AdminUser@index');
$router->get('admin/users/delete/:id', 'Admin\AdminUser@delete');

$router->run();