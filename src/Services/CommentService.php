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

    public function moderate($action, $identifier, $showFlash = true)
    {
        switch ($action) {
        case '0':
            $flashMsg = 'La modération du commentaire a été annulée';
            break;
        case '1':
            $flashMsg = 'Le commentaire a été accepté';
            break;
        case '2':
            $flashMsg = 'Le commentaire a été refusé';
            break;
        }
        $this->update($identifier, ["moderate" => $action], $flashMsg, false, $showFlash);
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
            $this->moderate($action, $identifier, false);
        }
        $this->flashServices->success("Commentaires modifiés", $flash);
    }
}
