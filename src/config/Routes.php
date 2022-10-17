<?php

namespace Application\config;

use Core\Router\Router;

class Routes
{
    public function __construct()
    {
        $this->router = new Router($this->url());
        $this->setRoutes();
    }

    private function url()
    {
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
        } else {
            $url = '/';
        }
        return $url;
    }

    private function setRoutes()
    {
        // FRONTEND //
        // Home
        $this->router->get('/', 'Home@execute', 'home');
        $this->router->post('/', 'Home@send');
        // Post
        $this->router->get('posts', 'Post@index', 'posts');
        $this->router->get('posts/categories', 'Post@categories');
        $this->router->get('posts/:id', 'Post@index' , 'post:id');
        $this->router->get('post/:id', 'Post@show', 'post');
        // Comment
        $this->router->post('post/:id', 'Comment@add');
        $this->router->post('comment/delete', 'Comment@delete');
        // $this->router->get('comment/delete', 'Comment@delete');
        $this->router->post('comment/cancelDelete', 'Comment@cancelDelete');
        $this->router->get('comment/:id', 'Comment@update');
        $this->router->post('comment/:id', 'Comment@update');
        // SECURITY //
        // User
        $this->router->post('profil/ajax/checkUsername', 'Security\User@checkUsername');
        $this->router->get('profil', 'Security\User@show');
        $this->router->get('profil/edit', 'Security\User@update');
        $this->router->post('profil/edit', 'Security\User@update');
        $this->router->get('profil/edit/mail', 'Security\User@edit_mail');
        $this->router->post('profil/edit/mail', 'Security\User@edit_mail');
        $this->router->get('profil/edit/password', 'Security\User@edit_password');
        $this->router->post('profil/edit/password', 'Security\User@edit_password');
        $this->router->get('profil/delete/image', 'Security\User@delete_picture');
        $this->router->get('profil/register', 'Security\User@register');
        $this->router->post('profil/register', 'Security\User@register');
        $this->router->get('profil/:id', 'Security\User@show');
        $this->router->get('login', 'Security\User@login');
        $this->router->post('login', 'Security\User@login');
        $this->router->get('logout', 'Security\User@logout');
        $this->router->get('forget', 'Security\User@forget_password');
        $this->router->post('forget', 'Security\User@forget_password');
        $this->router->get('reset_password/:token', 'Security\User@reset_password');
        $this->router->post('reset_password/:token', 'Security\User@reset_password');
        $this->router->get('confirm_again', 'Security\User@confirm_again');
        // Email Confirmation
        $this->router->get('confirmation/:token', 'Security\User@confirmation');
        // BACKEND //
        // Dashboard
        $this->router->get('dashboard', 'Admin\Dashboard@execute', 'dashboard');
        // AdminCategory
        $this->router->get('admin/categories', 'Admin\AdminCategory@index');
        $this->router->get('admin/category/add', 'Admin\AdminCategory@add');
        $this->router->post('admin/category/add', 'Admin\AdminCategory@add');
        $this->router->get('admin/category/update/:id', 'Admin\AdminCategory@update');
        $this->router->post('admin/category/update/:id', 'Admin\AdminCategory@update');
        $this->router->get('admin/category/delete/:id', 'Admin\AdminCategory@delete');
        // AdminPost
        $this->router->get('admin/posts', 'Admin\AdminPost@index', 'admin:posts');
        $this->router->get('admin/posts/add', 'Admin\AdminPost@add');
        $this->router->post('admin/posts/add', 'Admin\AdminPost@add');
        $this->router->get('admin/posts/update/:id', 'Admin\AdminPost@update');
        $this->router->post('admin/posts/update/:id', 'Admin\AdminPost@update');
        $this->router->get('admin/posts/delete/:id', 'Admin\AdminPost@delete');
        $this->router->get('admin/posts/:category', 'Admin\AdminPost@index');
        // AdminComment
        $this->router->get('admin/comments/moderate/:action/:id', 'Admin\AdminComment@moderate', 'admin:comment:moderate');
        $this->router->get('admin/comments/delete/:id', 'Admin\AdminComment@delete');
        $this->router->get('admin/comments/:filter', 'Admin\AdminComment@index');
        $this->router->post('admin/comments/:filter', 'Admin\AdminComment@multiple_moderation');
        $this->router->post('admin/comments', 'Admin\AdminComment@multiple_moderation');
        $this->router->get('admin/comments', 'Admin\AdminComment@index');
        // AdminUser
        $this->router->get('admin/users', 'Admin\AdminUser@index');
        $this->router->get('admin/users/delete/:id', 'Admin\AdminUser@delete');
        // Core //
        // AdminConfig
        $this->router->get('admin/config/update/:name', 'Config@update@core');
        $this->router->post('admin/config/update/:name', 'Config@update@core');
        $this->router->get('admin/configs/list/:prefix', 'Config@list@core');
        $this->router->get('admin/configs', 'Config@index@core', 'admin:configs');
        // AdminDoc
        $this->router->get('admin/docs', 'Doc@index@core');
        // Init
        $this->router->get('init/missing_configs', 'Config@init_missing_configs@core');
        $this->router->get('init/configs', 'Config@init_configs@core');
        $this->router->get('init/tables', 'Config@init_tables@core');
        $this->router->get('init', 'Init@init@core', 'init');
        $this->router->get('new', 'Init@new@core', 'new');
        $this->router->get('create_bdd', 'Init@create@core');
    }

    public function run()
    {
        $this->router->run();
    }

    public function getUrl($name, $params = [])
    {
        return $this->router->url($name, $params);
    }
}
