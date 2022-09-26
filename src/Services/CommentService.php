<?php

namespace Application\Services;

use Application\Models\Comment;
use Core\Services\EntityService;

class CommentService extends EntityService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Comment();
    }

    public function getCommentsFiltered($filter)
    {
        $option = "";
        switch ($filter) {
        case "all":
            $option = "";
            break;
        case "pending":
            $option = " WHERE moderate = 0";
            break;
        case "approved":
            $option = " WHERE moderate = 1";
            break;
        case "rejected":
            $option = " WHERE moderate = 2";
            break;
        }
        $params = $this->getAll($option);
        if (!$params) {
            throw new \Exception('Impossible de récupérer les commentaires !');
        }
        return $params;
    }

    // public function getComment($identifier)
    // {
    //     $params['comment'] = $this->commentRepository->getComment($identifier);
    //     if ($params === null) {
    //         throw new \Exception("Le commentaire $identifier n'existe pas.");
    //     }
    //     return $params;
    // }

    // public function delete($identifier)
    // {
    //     $success = $this->commentRepository->delete($identifier);
    //     if (!$success) {
    //         throw new \Exception('Impossible de supprimer le commentaire !');
    //     }
    //     $this->flashServices->danger(
    //         'Commentaire supprimé',
    //         'Le commentaire a bien été supprimé'
    //     );
    // }

    public function update($identifier, $input)
    {
        $comment = $this->validateForm($input, ["comment"]);
        $success = $this->commentRepository->update($identifier, $comment);
        $success = $this->commentRepository->moderate("0", $identifier);
        if (!$success) {
            throw new \Exception('Impossible de modifier le commentaire !');
        }
        $this->flashServices->success(
            'Commentaire modifié',
            'Votre commentaire sera à nouveau <strong style="color:#f00;">soumis à la modération</strong> et publié'
        );
    }

    // public function add(string $post, array $input)
    // {
    //     $comment = $this->validateForm($input, ["comment"]);
    //     $success = $this->commentRepository->addComment($post, $comment->comment);
    //     if (!$success) {
    //         throw new \Exception('Impossible d\'ajouter le commentaire !');
    //     }
    //     $this->flashServices->success(
    //         'COMMENTAIRE ENVOYÉ',
    //         'Votre commentaire sera <strong style="color:#f00;">soumis à la modération</strong> avant d\'être publié'
    //     );
    // }

    public function moderate($action, $identifier)
    {
        $success = $this->commentRepository->moderate($action, $identifier);
        if (!$success) {
            throw new \Exception('Impossible de modifier le commentaire !');
        }
        switch ($action) {
        case '0':
            $this->flashServices->success('Commentaire modifié', 'La modération du commentaire a été annulée');
            break;
        case '1':
            $this->flashServices->success('Commentaire modifié', 'Le commentaire a été accepté');
            break;
        case '2':
            $this->flashServices->danger('Commentaire modifié', 'Le commentaire a été refusé');
            break;
        }
    }

    public function multiple_moderation($input)
    {
        switch ($input['btnSubmit']) {
        case 'Invalider':
            $action = "0";
            $flash = 'La modération des commentaires a été annulée';
            break;
        case 'Valider':
            $action = "1";
            $flash = 'Les commentaires ont bien été validés';
            break;
        case 'Refuser':
            $action = "2";
            $flash = 'Les commentaires ont bien été refusés';
            break;
        }
        foreach ($input['comment'] as $identifier) {
            $success = $this->commentRepository->moderate($action, $identifier);
            if (!$success) {
                throw new \Exception('Une erreur est survenue lors de la modification du commentaire #'. $identifier .' !');
            }
        }
        $this->flashServices->success("Commentaires modifiés", $flash);
    }
}
