<?php

namespace Core;

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
        $this->commentRepository = new CommentRepository();
        $this->userRepository = new UserRepository();
        $this->tokenRepository = new TokenRepository();
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