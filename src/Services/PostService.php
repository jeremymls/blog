<?php

namespace Application\Services;

use Application\Models\Post;
use Core\Services\EntityService;

class PostService extends EntityService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new Post();
    }

    public function delete_post_picture($identifier)
    {
        $success = $this->repository->update($identifier, ['picture' => null]);
        if (!$success) {
            throw new \Exception("Impossible de supprimer la photo de profil");
        }
        $this->flashServices->success(
            'Image de la publication supprimée',
            "L'image de la publication a bien été supprimée"
        );
    }
}
