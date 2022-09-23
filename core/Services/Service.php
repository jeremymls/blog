<?php

namespace Core\Services;

use Application\Repositories\CommentRepository;
use Application\Repositories\PostRepository;
use Application\Repositories\UserRepository;
use Core\Repositories\TokenRepository;
use Core\Middleware\Pagination;

class Service
{
    public function __construct()
    {
        $this->flashServices = new FlashService();
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
        if (!in_array("ko", $conditions) ) {
            foreach ($input as $key => $value) {
                $this->model->$key = $value;
            } 
            return $this->model;
        }else {
            throw new \Exception('Les données du formulaire sont invalides.');
        }
    }
}
