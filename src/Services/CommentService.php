<?php

namespace Application\Services;

use Core\Service;
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
        $params= $this->pagination($params, 'comments', 10);
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

    public function commentValidate($identifier)
    {
        $success = $this->commentRepository->commentValidate($identifier);
        if (!$success) {
            throw new \Exception('Impossible de valider le commentaire !');
        }
        $this->flash(
            'success',
            'Commentaire validé',
            'Le commentaire a bien été validé'
        );
    }

    public function delete($identifier)
    {
        $success = $this->commentRepository->delete($identifier);
        if (!$success) {
            throw new \Exception('Impossible de supprimer le commentaire !');
        }
        $this->flash(
            'danger',
            'Commentaire supprimé',
            'Le commentaire a bien été supprimé'
        );
    }

    public function update($identifier, $input)
    {
        $comment = $this->validateForm($input,["comment"]);
        $success = $this->commentRepository->update($identifier, $comment);
        $success = $this->commentRepository->setPending($identifier);
        if (!$success) {
            throw new \Exception('Impossible de modifier le commentaire !');
        }
        $this->flash(
            'success',
            'Commentaire modifié',
            'Votre commentaire sera à nouveau <strong>soumis à la modération</strong> et publié'
        );
    }

    public function add(string $post, array $input)
    {
        $comment = $this->validateForm($input,["comment"]);
        $success = $this->commentRepository->commentAdd($post, $comment->comment);
        if (!$success) {
            throw new \Exception('Impossible d\'ajouter le commentaire !');
        }
        $this->flash(
            'success',
            'COMMENTAIRE ENVOYÉ',
            'Votre commentaire sera <strong>soumis à la modération</strong> avant d\'être publié'
        );
    }
}