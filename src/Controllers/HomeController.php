<?php

namespace Application\Controllers;

use Application\Services\HomeService;
use Core\Controllers\Controller;
use Application\Services\PostService;

/**
 * HomeController
 * 
 * Home Controller
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
     * execute
     * 
     * Display the home page
     */
    public function execute()
    {
        $params = $this->postService->getAll("", [], "LIMIT 5");
        $this->twig->display('homepage.twig', $params);
    }

    /**
     * send
     * 
     * Send a contact mail
     */
    public function send()
    {
        if ($this->isPost()) {
            $this->homeService->sendContactMail($this->superglobals->getPost());
        }
        $this->superglobals->redirect('home');
    }
}
