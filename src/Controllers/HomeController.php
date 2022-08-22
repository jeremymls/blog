<?php

namespace Application\Controllers;

use Core\Controller;
use Application\Services\PostService;
use Core\Services\MailService;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->postService = new PostService();
    }

    public function execute()
    {
        $params = $this->postService->getPosts("", [], "LIMIT 5");
        $this->twig->display('homepage.twig', $params);
    }

    public function send()
    {
        $mailService = new MailService();
        $mailService->sendContactEmail($_POST);
        header('Location: /');
    }
}
