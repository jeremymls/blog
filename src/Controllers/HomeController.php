<?php

namespace Application\Controllers;

use Application\Services\HomeService;
use Core\Controllers\Controller;
use Application\Services\PostService;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
        $this->homeService = new HomeService();
    }

    public function execute()
    {
        $params = $this->postService->getAll("", [], "LIMIT 5");
        $this->twig->display('homepage.twig', $params);
    }

    public function send()
    {
        $this->homeService->sendContactMail();
        header('Location: /');
    }
}
