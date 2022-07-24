<?php

namespace Application\Services;

use Application\Repositories\CommentRepository;
use Application\Lib\DatabaseConnection;
use Application\Models\Comment;

class CommentService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Comment();
    }
    public function getCommentsForBo($filter)
    {
        $params['comments'] = $this->commentRepository->getCommentsForBo($filter);
        if (!$params) {
            throw new \Exception('Impossible de récupérer les commentaires !');
        }
        $params['filter'] = $filter;
        return $params;
    }

    public function getComment($identifier)
    {
        $params['comment'] = $this->commentRepository->getComment($identifier);
        if ($params === null) {
            throw new \Exception("Le commentaire $identifier n'existe pas.");
        }
        return $params;
    }

    public function validateComment($identifier)
    {
        $success = $this->commentRepository->validateComment($identifier);
        if (!$success) {
            throw new \Exception('Impossible de valider le commentaire !');
        }
    }

    public function delete($identifier)
    {
        $success = $this->commentRepository->delete($identifier);
        if (!$success) {
            throw new \Exception('Impossible de supprimer le commentaire !');
        }
    }

    public function update($identifier, $input)
    {
        $comment = $this->validateForm($input,["comment"]);
        $success = $this->commentRepository->update($identifier, $comment);
        $success = $this->commentRepository->setPending($identifier);
        if (!$success) {
            throw new \Exception('Impossible de modifier le commentaire !');
        }
    }

    public function add(string $post, array $input)
    {
        $comment = $this->validateForm($input,["comment"]);
        $success = $this->commentRepository->addComment($post, $comment->comment);
        if (!$success) {
            throw new \Exception('Impossible d\'ajouter le commentaire !');
        }
    }
}