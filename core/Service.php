<?php

namespace Core;

use Application\Repositories\CommentRepository;
use Application\Lib\DatabaseConnection;
use Application\Repositories\PostRepository;
use Application\Repositories\UserRepository;

class Service
{

    public function __construct()
    {
        $this->postRepository = new PostRepository();
        $this->postRepository->connection = new DatabaseConnection();
        $this->commentRepository = new CommentRepository();
        $this->commentRepository->connection = new DatabaseConnection();
        $this->userRepository = new UserRepository();
        $this->userRepository->connection = new DatabaseConnection();
    }

    public function validateForm(array $input, array $requiredFields = [])
    {
        $conditions = [];
        foreach ($requiredFields as $key => $field) {
            $conditions[] = !empty($input[$field])?'ok':'ko';
        }
        if (!in_array("ko", $conditions) ){
            foreach ($input as $key => $value) {
                $this->model->$key = $value;
            } 
        return $this->model;
        }else {
                throw new \Exception('Les données du formulaire sont invalides.');
        }
    }

    public function pagination($params, $entities, $nbp = 3)
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        $nbPage = ceil(count($params[$entities]) / $nbp);
        $params[$entities] = array_slice($params[$entities], ($page - 1) * $nbp, $nbp);
        $params['nbPage'] = $nbPage;
        return $params;
    }

    public function flash(string $type, string $title, string $message)
    {
        $html = '<div class="container"><div class="alert alert-';
        $html .= $type;
        $html .= ' alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3><strong>';
        $html .= strtoupper($title);
        $html .= '</strong></h3><p>';
        $html .= $message;
        $html .= '</p></div></div>';
        setcookie("flash", $html, time() + 3, "/");
    }
}