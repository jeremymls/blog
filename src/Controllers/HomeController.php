<?php

namespace Application\Controllers;

use Core\Controllers\Controller;
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
        $mailService->sendEmail([
            'reply_to' => [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ],
            'recipient' => $mailService->getOwnerMail(),
            'subject' => 'Message de ' . $_POST['name'],
            'template' => 'contact',
            'template_data' => [
                'name' => $_POST['name'],
                'mail' => $_POST['email'],
                'phone' => $_POST['phone'],
                'message' => $_POST['message'],
                // todo: dynamise site name
                'site_name' => 'JM projets'
            ],
            'success_message' => 'Votre message a bien été envoyé.']
        );
        header('Location: /');
    }
}
