<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Services;

use Application\Models\Post;
use Core\Services\EntityService;

/**
 * PostService
 *
 * Post Service
 *
 * @category Application
 * @package  Application\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class PostService extends EntityService
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Post();
    }

    /**
     * Delete Post Picture
     *
     * @param mixed $identifier Identifier
     *
     * @return void
     */
    public function deletePostPicture($identifier)
    {
        $entity = new Post();
        $entity->picture = "";
        $entity->csrf_token = $this->superglobals->getPost('csrf_token');
        $success = $this->repository->update($identifier, $entity);
        if (!$success) {
            throw new \Exception("Impossible de supprimer la photo de profil");
        }
        $this->flashServices->danger(
            'Image de la publication supprimée',
            "L'image de la publication a bien été supprimée"
        );
    }
}
