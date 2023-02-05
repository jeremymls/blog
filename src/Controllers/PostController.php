<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Controllers;

use Application\Services\CategoryService;
use Application\Services\CommentService;
use Core\Controllers\Controller;
use Application\Services\PostService;
use Core\Middleware\Superglobals;
use Core\Services\FlashService;

/**
 * PostController
 *
 * Post Controller
 *
 * @category Application
 * @package  Application\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class PostController extends Controller
{
    private $postService;
    private $commentService;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
        $this->commentService = new CommentService();
    }

    /**
     * Index
     *
     * Display the posts list
     *
     * @param mixed $category the category identifier
     *
     * @return void
     */
    public function index($category = null)
    {
        $option = $category !== null ? "WHERE category = ?" : "";
        $optionsData = $category !== null ? [$category] : [];
        $categoryService = new CategoryService();
        $params = $this->multiParams(
            [
            $this->postService->getAll($option, $optionsData),
            $categoryService->getBy('id = ?', [$category])
            ]
        );
        if ($category && $params['category']->name == "") {
            FlashService::getInstance()->danger(
                "Erreur",
                "La catégorie demandée n'existe pas <br>
                Sélectionnez une catégorie dans la liste"
            );
            Superglobals::getInstance()->redirect('posts:categories');
        }
        $params = $this->pagination->paginate($params, 'posts', 3);
        $this->twig->display('post/index.twig', $params);
    }

    /**
     * Show
     *
     * Display a post
     *
     * @param mixed $identifier the post identifier
     *
     * @return void
     */
    public function show(string $identifier)
    {
        $params = $this->multiParams(
            [
                $this->postService->get($identifier),
                $this->commentService->getAll(
                    'WHERE post = :post_id and (moderate = 1 or author = 1)',
                    ['post_id' => $identifier]
                )
            ]
        );
        $params = $this->pagination->paginate($params, 'comments', 5);
        $this->twig->display('post/show.twig', $params);
    }

    /**
     * Categories
     *
     * Display the categories list
     *
     * @return void
     */
    public function categories()
    {
        $this->twig->display('post/categories.twig');
    }
}
