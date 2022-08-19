<?php

namespace Core;

use Application\Lib\DatabaseConnection;
use Application\Repositories\CommentRepository;
use Application\Repositories\PostRepository;
use Application\Repositories\TokenRepository;
use Application\Repositories\UserRepository;
use Core\Middleware\Pagination;
use Core\Services\Flash;

class Service
{
    public function __construct()
    {
        $this->flash = new Flash();
        $this->pagination = new Pagination();
        $this->postRepository = new PostRepository();
        $this->postRepository->connection = new DatabaseConnection();
        $this->commentRepository = new CommentRepository();
        $this->commentRepository->connection = new DatabaseConnection();
        $this->userRepository = new UserRepository();
        $this->userRepository->connection = new DatabaseConnection();
        $this->tokenRepository = new TokenRepository();
        $this->tokenRepository->connection = new DatabaseConnection();
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
}