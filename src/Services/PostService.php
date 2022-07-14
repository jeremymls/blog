<?php

namespace Application\Services;

use Application\Repositories\PostRepository;
use Application\Repositories\CommentRepository;
use Application\Lib\DatabaseConnection;

class PostService
{

    public function __construct()
    {
        $this->postRepository = new PostRepository();
        $this->postRepository->connection = new DatabaseConnection();
        $this->commentRepository = new CommentRepository();
        $this->commentRepository->connection = new DatabaseConnection();
    }

    public function getPosts()
    {
        $posts = $this->postRepository->getPosts();
        return [
            'posts' => $posts['data'],
            'nbPage' => $posts['nbPage']
        ];
    }

    public function getPost($identifier)
    {
        $post = $this->postRepository->getPost($identifier);
        return [
            'post' => $post,
        ];
    }

    public function getPostWithComments($identifier)
    {
        $post = $this->postRepository->getPost($identifier);
        $comments = $this->commentRepository->getCommentsByPost($identifier);
        return [
            'post' => $post,
            'comments' => $comments,
        ];
    }

    public function add($input)
    {
        $title = null;
        $content = null;
        if (!empty($input['title']) && !empty($input['content'])) {
            $title = $input['title'];
            $content = $input['content'];
            $url = $input['url'];
            $chapo = $input['chapo'];
        } else {
            throw new \Exception('Les données du formulaire sont invalides.');
        }

        $success = $this->postRepository->add($title, $content, $url, $chapo);
        if (!$success) {
            throw new \Exception('Impossible de modifier le commentaire !');
        } 
    }

    public function update($identifier, $input)
    {
        $title = null;
        $content = null;
        if (!empty($input['title']) && !empty($input['content'])) {
            $title = $input['title'];
            $content = $input['content'];
            $url = $input['url'];
            $chapo = $input['chapo'];
        } else {
            throw new \Exception('Les données du formulaire sont invalides.');
        }

        $success = $this->postRepository->update($identifier, $title, $content, $url, $chapo);
        if (!$success) {
            throw new \Exception('Impossible de modifier le commentaire !');
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