<?php

namespace Application\Services;

use Application\Repositories\CommentRepository;
use Application\Lib\DatabaseConnection;
use Application\Models\Comment;
use Application\Models\Post;
use Application\Repositories\PostRepository;
use Application\Repositories\UserRepository;

class Service
{

    public function __construct()
    {
        $this->postRepository = new PostRepository();
        $this->postRepository->connection = new DatabaseConnection();
        $this->post = new Post();

        $this->commentRepository = new CommentRepository();
        $this->commentRepository->connection = new DatabaseConnection();
        $this->comment = new Comment();
        
        $this->userRepository = new UserRepository();
        $this->userRepository->connection = new DatabaseConnection();
        $this->user = new UserRepository();
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
}