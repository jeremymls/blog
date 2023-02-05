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

use Application\Services\HomeService;
use Core\Controllers\Controller;
use Application\Services\PostService;

/**
 * HomeController
 *
 * Home Controller
 *
 * @category Application
 * @package  Application\Controllers
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class HomeController extends Controller
{
    private $postService;
    private $homeService;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
        $this->homeService = new HomeService();
    }

    /**
     * Execute
     *
     * Display the home page
     *
     * @return void
     */
    public function execute()
    {
        $params = $this->postService->getAll("", [], "LIMIT 5");
        $this->twig->display('homepage.twig', $params);
    }

    /**
     * Send
     *
     * Send a contact mail
     *
     * @return void
     */
    public function send()
    {
        if ($this->isPost()) {
            $this->homeService->sendContactMail($this->superglobals->getPost());
        }
        $this->superglobals->redirect('home');
    }
}
