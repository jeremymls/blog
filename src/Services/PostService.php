<?php

namespace Application\Services;

use Application\Models\Post;

class PostService extends Service
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new Post();
    }

    public function getPosts()
    {
        $params['posts'] = $this->postRepository->findAll();
        return $params;
    }

    public function getPost($identifier)
    {
        $params['posts'] = $this->postRepository->findOne($identifier);
        return $params;
    }

    public function getPostWithComments($identifier)
    {
        $params['post'] = $this->postRepository->findOne($identifier);
        $params['comments'] = $this->commentRepository->getCommentsByPost($identifier);
        return $params;
    }

    public function add($input)
    {
        $post = $this->validateForm($input,["title", "content"]);
        $success = $this->postRepository->add($post);
        if (!$success) {
            throw new \Exception('Impossible de d\'ajouter le projet !');
        } 
    }

    public function update($identifier, $input)
    {
        $post = $this->validateForm($input,["title", "content"]);
        $success = $this->postRepository->update($identifier, $post);
        if (!$success) {
            throw new \Exception('Impossible de modifier le projet !');
        } 
    }

    public function delete($identifier)
    {
        $success = $this->postRepository->delete($identifier);
        if (!$success) {
            throw new \Exception('Impossible de supprimer le projet !');
        } 
    }
}