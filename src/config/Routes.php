<?php

namespace Application\config;

use Core\Middleware\Superglobals;
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
        $url = Superglobals::getInstance()->getGet('url');
        return $url ?? '/';
    }

    private function setRoutes()
    {
        // FRONTEND //
        // Home
        $this->router->get('/', 'Home@execute', 'home');
        $this->router->post('/', 'Home@send');
        // Post
        $this->router->get('posts', 'Post@index', 'posts');
        $this->router->get('posts/categories', 'Post@categories', 'posts:categories');
        $this->router->get('posts/:id', 'Post@index' , 'post:id');
        $this->router->get('post/:id', 'Post@show', 'post');
        // Comment
        $this->router->post('post/:id', 'Comment@add');
        $this->router->post('comment/ajax/:delete', 'Comment@ajax');
        $this->router->get('comment/:id', 'Comment@update', 'comment:update');
        $this->router->post('comment/:id', 'Comment@update');
        // SECURITY //
        // User
        $this->router->post('profil/ajax/checkUsername', 'Security\User@checkUsername');
        $this->router->get('profil', 'Security\User@show', 'profil');
        $this->router->get('profil/edit', 'Security\User@update', 'profil:edit');
        $this->router->post('profil/edit', 'Security\User@update');
        $this->router->get('profil/edit/mail', 'Security\User@edit_mail', 'profil:edit:mail');
        $this->router->post('profil/edit/mail', 'Security\User@edit_mail');
        $this->router->get('profil/edit/password', 'Security\User@edit_password', 'profil:edit:password');
        $this->router->post('profil/edit/password', 'Security\User@edit_password');
        $this->router->get('profil/delete/image', 'Security\User@delete_picture', 'profil:delete:image');
        $this->router->get('profil/register', 'Security\User@register', 'register');
        $this->router->post('profil/register', 'Security\User@register');
        $this->router->get('profil/:id', 'Security\User@show', 'profil:id');
        $this->router->get('login/:anchor', 'Security\User@login', 'login:anchor');
        $this->router->post('login/:anchor', 'Security\User@login');
        $this->router->get('login', 'Security\User@login', 'login');
        $this->router->post('login', 'Security\User@login');
        $this->router->get('logout', 'Security\User@logout', 'logout');
        $this->router->get('forget', 'Security\User@forget_password', 'forget');
        $this->router->post('forget', 'Security\User@forget_password');
        $this->router->get('reset_password/:token', 'Security\User@reset_password', 'reset_password');
        $this->router->post('reset_password/:token', 'Security\User@reset_password');
        $this->router->get('confirm_again', 'Security\User@confirm_again', 'confirm_again');
        $this->router->get('redirect', 'Security\User@redirect', 'redirect');
        // Email Confirmation
        $this->router->get('confirmation/:token', 'Security\User@confirmation', 'confirmation');
        // BACKEND //
        // Dashboard
        $this->router->get('dashboard', 'Admin\Dashboard@execute', 'dashboard');
        // AdminCategory
        $this->router->get('admin/categories', 'Admin\AdminCategory@index', 'admin:categories');
        $this->router->get('admin/category/add', 'Admin\AdminCategory@add', 'admin:category:add');
        $this->router->post('admin/category/add', 'Admin\AdminCategory@add');
        $this->router->get('admin/category/update/:id', 'Admin\AdminCategory@update', 'admin:category:update');
        $this->router->post('admin/category/update/:id', 'Admin\AdminCategory@update');
        $this->router->get('admin/category/delete/:id', 'Admin\AdminCategory@delete', 'admin:category:delete');
        // AdminPost
        $this->router->get('admin/posts', 'Admin\AdminPost@index', 'admin:posts');
        $this->router->get('admin/posts/add', 'Admin\AdminPost@add', 'admin:post:add');
        $this->router->post('admin/posts/add', 'Admin\AdminPost@add');
        $this->router->get('admin/posts/update/:id', 'Admin\AdminPost@update', 'admin:post:update');
        $this->router->post('admin/posts/update/:id', 'Admin\AdminPost@update');
        $this->router->get('admin/posts/delete/picture/:id', 'Admin\AdminPost@delete_picture', 'admin:post:delete:picture');
        $this->router->get('admin/posts/delete/:id', 'Admin\AdminPost@delete', 'admin:post:delete');
        $this->router->get('admin/posts/:category', 'Admin\AdminPost@index', 'admin:posts:category');
        $this->router->get('admin/posts/:category/:nbr_show', 'Admin\AdminPost@index', 'admin:posts:category:nbr_show');
        // AdminComment
        $this->router->get('admin/comments/moderate/:action/:id', 'Admin\AdminComment@moderate', 'admin:comment:moderate');
        $this->router->get('admin/comments/delete/:id', 'Admin\AdminComment@delete');
        $this->router->get('admin/comments/:filter', 'Admin\AdminComment@index', 'admin:comments:filter');
        $this->router->post('admin/comments/:filter', 'Admin\AdminComment@multiple_moderation');
        $this->router->get('admin/comments/:filter/:nbr_show', 'Admin\AdminComment@index');
        $this->router->post('admin/comments/:filter/:nbr_show', 'Admin\AdminComment@multiple_moderation');
        $this->router->post('admin/comments', 'Admin\AdminComment@multiple_moderation');
        $this->router->get('admin/comments', 'Admin\AdminComment@index', 'admin:comments');
        // AdminUser
        $this->router->get('admin/users', 'Admin\AdminUser@index', 'admin:users');
        $this->router->get('admin/users/:nbr_show', 'Admin\AdminUser@index');
        $this->router->get('admin/users/delete/:id', 'Admin\AdminUser@delete', 'admin:user:delete');
        $this->router->get('admin/users/role/:id', 'Admin\AdminUser@role', 'admin:user:role');
        $this->router->post('admin/users/role/:id', 'Admin\AdminUser@role');
        // Core //
        // AdminConfig
        $this->router->get('admin/config/update/:id', 'Config@update@core', 'admin:config:update');
        $this->router->post('admin/config/update/:id', 'Config@update@core');
        $this->router->get('admin/config/delete/:value', 'Config@delete_value@core', 'admin:config:delete:value');
        $this->router->get('admin/configs/list/:prefix', 'Config@list@core', 'admin:configs:list');
        $this->router->get('admin/configs', 'Config@index@core', 'admin:configs');
        // AdminDoc
        $this->router->get('admin/docs', 'Doc@index@core', 'admin:docs');
        // Init
        $this->router->get('init/missing_configs', 'Init@init_missing_configs@core', 'init:missing_configs');
        $this->router->get('init/configs', 'Init@init_configs@core', 'init:configs');
        $this->router->get('init/tables', 'Init@init_tables@core', 'init:tables');
        $this->router->get('init', 'Init@init@core', 'init');
        $this->router->get('new', 'Init@new@core', 'new');
        $this->router->get('create_bdd', 'Init@create@core', 'create_bdd');
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
