<?php

namespace Application\Services;

use Core\Service;
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
        $params= $this->pagination($params, 'posts',5);
        return $params;
    }

    public function getPost($identifier)
    {
        $params['post'] = $this->postRepository->findOne($identifier);
        return $params;
    }

    public function getPostWithComments($identifier)
    {
        $params['post'] = $this->postRepository->findOne($identifier);
        $params['comments'] = $this->commentRepository->getCommentsByPost($identifier);
        $params= $this->pagination($params, 'comments', 4);
        return $params;
    }

    public function add($input)
    {
        $post = $this->validateForm($input,["title", "content"]);
        $success = $this->postRepository->add($post);
        if (!$success) {
            throw new \Exception('Impossible de d\'ajouter le projet !');
        } 
        $this->flash->success(
            'Projet ajouté',
            'Le projet '. $_POST['title'] .' a bien été ajouté'
        );
    }

    public function update($identifier, $input)
    {
        $post = $this->validateForm($input,["title", "content"]);
        $success = $this->postRepository->update($identifier, $post);
        if (!$success) {
            throw new \Exception('Impossible de modifier le projet !');
        } 
        $this->flash->success(
            'Projet modifié',
            'Le projet a bien été modifié'
        );
    }

    public function delete($identifier)
    {
        $success = $this->postRepository->delete($identifier);
        if (!$success) {
            throw new \Exception('Impossible de supprimer le projet !');
        } 
        $this->flash->danger(
            'Projet supprimé',
            'Le projet a bien été supprimé'
        );
    }
}