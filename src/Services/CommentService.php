<?php

namespace Application\Services;

use Application\Repositories\CommentRepository;
use Application\Lib\DatabaseConnection;

class CommentService
{

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
        $this->commentRepository->connection = new DatabaseConnection();
    }

    public function getUnmoderatedComments()
    {
        $comments = $this->commentRepository->getUnmoderatedComments();
        return [
            'comments' => $comments,
        ];
    }

    public function getCommentsForModeration($identifier)
    {
        $comment = $this->commentRepository->getComment($identifier);
        $comments = $this->commentRepository->getComments($comment->post);
        return [
            'comment' => $comment,
            'comments' => $comments,
        ];
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
        $success = $this->commentRepository->deleteComment($identifier);
        if (!$success) {
            throw new \Exception('Impossible de supprimer le commentaire !');
        }
    }

    public function update($identifier, $input)
    {
        $comment = null;
        if (!empty($input['comment'])) {
            $comment = $input['comment'];
        } else {
            throw new \Exception('Les données du formulaire sont invalides.');
        }

        $success = $this->commentRepository->updateComment($identifier, $comment);
        if (!$success) {
            throw new \Exception('Impossible de modifier le commentaire !');
        }
    }

    public function getComment($identifier)
    {
        $comment = $this->commentRepository->getComment($identifier);
        if ($comment === null) {
            throw new \Exception("Le commentaire $identifier n'existe pas.");
        }
        return [
            'comment' => $comment,
        ];
    }

    public function addComment(string $post, array $input)
    {
        $comment = null;
        if (!empty($input['comment'])) {
            $comment = $input['comment'];
        } else {
            throw new \Exception('Les données du formulaire sont invalides.');
        }

        $commentRepository = new CommentRepository();
        $commentRepository->connection = new DatabaseConnection();
        $success = $commentRepository->addComment($post, $comment);
        if (!$success) {
            throw new \Exception('Impossible d\'ajouter le commentaire !');
        }
    }

}